<?php
/**
 * Created by PhpStorm.
 * User: Jean-Pierre Lambelet (jlambelet@contentfleet.com)
 * Date: 16/03/16
 * Time: 19:29
 */

namespace AppBundle\Controller;


use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PlaceRestController extends Controller
{
    /**
     * @Route("/api/places", name="api_places")
     */
    public function getPlacesAction(){
        $places = $this->getDoctrine()->getRepository('AppBundle:Place')->findAll();
        /** @var \AppBundle\Entity\Place $place */
        foreach($places as $place){
            $infoBox = $this->render('AppBundle:GoogleMap:infoBox.html.twig', array('place' => $place));
            $place->setGoogleInfoBox($infoBox->getContent());
        }
        
        
        
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($places, 'json');
        return new Response($jsonContent);
    }
}