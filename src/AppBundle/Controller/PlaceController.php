<?php
/**
 * Created by PhpStorm.
 * User: Jean-Pierre Lambelet (jlambelet@contentfleet.com)
 * Date: 16/03/16
 * Time: 16:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Place;
use AppBundle\Entity\PlaceImage;
use AppBundle\Form\Type\ImageType;
use AppBundle\Form\Type\PlaceType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PlaceController extends Controller
{

    /**
     * @Route("/place/{slug}-{id}",
     *          requirements={
     *              "slug" = "[^/]+",
     *              "id" = "\d+"},
     *          name="place")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function placeAction($slug, $id, Request $request)
    {

        $template = 'AppBundle:Place:place.html.twig';
        $data = [];


        $id = intval($id);
        $place = $this->getDoctrine()->getRepository('AppBundle:Place')->findOneBy(['id' => $id], null, 10);
        if (!$place) {
            throw $this->createNotFoundException('404 - Seite nicht gefunden');
        }
        if ($place->getSlug() != $slug) {
            $redirectUrl = $this->generateUrl('place', ['id' => $place->getId(), 'slug' => $place->getSlug()]);
            return $this->redirect($redirectUrl);
        }
        $data['place'] = $place;
        
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') != true) {

            $template = 'AppBundle:Place:placeEdit.html.twig';
            $form = $this->createForm(PlaceType::class, $place, array('method' => 'PATCH'));
            $data['form'] = $form->createView();

            if ($request->request->has('place')) {
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $place = $form->getData();
                    $this->savePlaceData($place);
                }
            }

            $imageForm = $this->createForm(ImageType::class);
            $data['imageForm'] = $imageForm->createView();

            if ($request->request->has('image')) {
                $imageForm->handleRequest($request);
                if ($imageForm->isSubmitted() && $imageForm->isValid()) {
                    $image = $imageForm->getData();
                    $image->addPlace($place);
                    $imageFile = $image->getImageFile();
                    if ($imageFile) {
                        $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                        $imageFile->move(
                            $this->getParameter('placeImage_directory'),
                            $imageName
                        );
                        $image->setImageName($imageName);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($image);
                        $em->flush();
                    }
                }

            }
        }

        return $this->render($template, $data);
    }

    /**
     * @Route("/newPlace", name="place_form")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formPlaceAction(Request $request)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') != true) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $form = $this->createForm(PlaceType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $place = $form->getData();
            $this->savePlaceData($place);
            $redirectUrl = $this->generateUrl('place', ['id' => $place->getId(), 'slug' => $place->getSlug()]);
            return $this->redirect($redirectUrl);
        }

        return $this->render('AppBundle:Place:form.html.twig'
            , array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/find-location", name="find-location")
     * @method GET
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function findLocationAction(Request $request)
    {
        $address = $request->get('address');

        /** @var \Geocoder\Model\AddressCollection $addressCollection */
        $addressCollection = $this->getDoctrine()
            ->getRepository('AppBundle:Place')
            ->getGoogleAddress($address);

        $currentAddress = $addressCollection->first();

        return $this->render('AppBundle:Place:findLocation.html.twig', ['currentAddress' => $currentAddress]);
    }

    public function savePlaceData($place)
    {
        /** @var \Geocoder\Model\AddressCollection $addressCollection */
        $addressCollection = $this->getDoctrine()
            ->getRepository('AppBundle:Place')
            ->getAddressCollection($place);

        /** @var \Geocoder\Model\Coordinates $address */
        if ($addressCollection->count() && $address = $addressCollection->get(0)) {
            $place->setLongitude($address->getLongitude());
            $place->setLatitude($address->getLatitude());
        }
        $em = $this->getDoctrine()->getManager();
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $image */
        foreach ($place->getImages() as $image) {
            $imageFile = $image->getImageFile();
            if ($imageFile) {
                $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('placeImage_directory'),
                    $imageName
                );
                $image->setImageName($imageName);
            }
        }
        $em->persist($place);
        $em->flush();

    }


}