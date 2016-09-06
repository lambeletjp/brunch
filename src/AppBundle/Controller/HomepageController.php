<?php
/**
 * Created by PhpStorm.
 * User: Jean-Pierre Lambelet (jlambelet@contentfleet.com)
 * Date: 12/03/16
 * Time: 16:10
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

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $places = $this->getDoctrine()->getRepository('AppBundle:Place')->findBy([],null,10);

        /** @var \AppBundle\Entity\Place $place */
        foreach($places as $place){
            $images = $this->getDoctrine()->getRepository('AppBundle:PlaceImage')->findBy(['placeId' => $place->getId()]);
            $place->setImages($images);
        }

        return $this->render('AppBundle:Homepage:homepage.html.twig',['places' => $places]);
    }
}