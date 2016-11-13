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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PlaceRestController extends Controller
{
    /**
     * @Route("/api/places", name="api_places")
     */
    public function getPlacesAction(Request $request){
        $longitude = (float) $request->get('lng');
        $latitude = (float) $request->get('lat');

        /** @var \AppBundle\Repository\PlaceRepository $placeRepository */
        $placeRepository = $this->getDoctrine()->getRepository('AppBundle:Place');
        $places = $placeRepository->findPointAtDistanceInKm($latitude,$longitude,5);
        /** @var \AppBundle\Entity\Place $place */
        foreach($places as $place){
            $infoBox = $this->render('AppBundle:GoogleMap:infoBox.html.twig', array('place' => $place));
            $place->setGoogleInfoBox($infoBox->getContent());
        }
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($places, 'json');
        return new Response($jsonContent);
    }

    function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
        // Calcul de la distance en degrés
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

        // Conversion de la distance en degrés à l'unité choisie (kilomètres, milles ou milles nautiques)
        switch($unit) {
            case 'km':
                $distance = $degrees * 111.13384; // 1 degré = 111,13384 km, sur base du diamètre moyen de la Terre (12735 km)
                break;
            case 'mi':
                $distance = $degrees * 69.05482; // 1 degré = 69,05482 milles, sur base du diamètre moyen de la Terre (7913,1 milles)
                break;
            case 'nmi':
                $distance =  $degrees * 59.97662; // 1 degré = 59.97662 milles nautiques, sur base du diamètre moyen de la Terre (6,876.3 milles nautiques)
        }
        return round($distance, $decimals);
    }

    function findPointToDistance($point_lat,$point_long,$distance)
    {
        $degrees = 0.05;
        $distance = 5;
    }
}