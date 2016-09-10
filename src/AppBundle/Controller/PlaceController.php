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
        $id = intval($id);
        $place = $this->getDoctrine()->getRepository('AppBundle:Place')->findOneBy(['id' => $id],null,10);
        if(!$place){
            throw $this->createNotFoundException('404 - Seite nicht gefunden');
        }
        if($place->getSlug() != $slug){
            $redirectUrl = $this->generateUrl('place',['id' => $place->getId(),'slug' => $place->getSlug()]);
            return $this->redirect($redirectUrl);
        }

        return $this->render('AppBundle:Place:place.html.twig',[
            'place' => $place,
            'placeImages' => []
        ]);
    }

    /**
     * @Route("/newPlace", name="place_form")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formPlaceAction(Request $request)
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Geocoder\Model\AddressCollection $addressCollection */
            $addressCollection = $this->getDoctrine()
                ->getRepository('AppBundle:Place')
                ->getAddressCollection($place);

            /** @var \Geocoder\Model\Coordinates $address */
            if($addressCollection->count() && $address = $addressCollection->get(0)){
                $place->setLongitude($address->getLongitude());
                $place->setLatitude($address->getLatitude());
                $em = $this->getDoctrine()->getManager();
                /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $image */
                if ($image = $place->getImages()) {
                    $place->setImages(new ArrayCollection());
                    $imageName = md5(uniqid()).'.'.$image->guessExtension();
                    $image->move(
                        $this->getParameter('placeImage_directory'),
                        $imageName
                    );
                    $placeImage = new Image();
                    $placeImage->setImageName($imageName);
                    $place->addImage($placeImage);
                }
                $em->persist($place);
                $em->flush();
            }

            $redirectUrl = $this->generateUrl('place',['id' => $place->getId(),'slug' => $place->getSlug()]);
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

        return $this->render('AppBundle:Place:findLocation.html.twig',['currentAddress' => $currentAddress]);
    }


}