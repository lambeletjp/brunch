<?php
/**
 * Created by PhpStorm.
 * User: Jean-Pierre Lambelet (jlambelet@contentfleet.com)
 * Date: 16/03/16
 * Time: 19:29
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class PlaceRestController extends Controller
{
    /**
     * @Route("/api/places", name="api_places")
     */
    public function getPlacesAction(){
        $places = $this->getDoctrine()->getRepository('AppBundle:Place')->findAll();
        $places = $this->getDoctrine()->getRepository('AppBundle:Place')->geoFormat($places);
        return new Response(json_encode($places));
    }
}