<?php
/**
 * Created by PhpStorm.
 * User: Jean-Pierre Lambelet (jlambelet@contentfleet.com)
 * Date: 16/03/16
 * Time: 16:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PlaceController extends Controller
{

    /**
     * @Route("/form", name="place_form")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formPlaceAction(Request $request)
    {
        $place = new Place();

        $form = $this->createFormBuilder($place)
            ->add('name', TextType::class)
            ->add('address', TextType::class)
            ->add('postalCode', NumberType::class)
            ->add('city', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Place'))
            ->getForm();

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
                $em->persist($place);
                $em->flush();
            }



            return $this->redirectToRoute('place_success');
        }

        return $this->render('AppBundle:Place:form.html.twig'
            , array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/form/success", name="place_success")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newPlaceAction(Request $request)
    {
        return $this->formPlaceAction($request);
    }
}