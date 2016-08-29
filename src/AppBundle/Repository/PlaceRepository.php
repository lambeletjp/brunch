<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Place;
use Geocoder\Provider\GoogleMaps;
use Ivory\HttpAdapter\CurlHttpAdapter;

/**
 * PlaceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlaceRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAddressCollection(Place $place)
    {
        return $this->getGoogleAddress($place->getGoogleAdress());
    }

    public function getGoogleAddress($address)
    {
        $curl     = new CurlHttpAdapter();
        $geocoder = new GoogleMaps($curl);
        return $geocoder->geocode($address);
    }

    public function geoFormat(Array $places){
        $placesGeo = [];
        foreach($places as $place){
            $placesGeo[] = $this->getPlaceGeoFormat($place);
        }

        $return = (object) [
            "type" => "FeatureCollection",
            "features" => $placesGeo
        ];

        return $return;
    }

    private function getPlaceGeoFormat(Place $place)
    {
//        "geometry": {"type": "Point", "coordinates": [102.0, 0.5]},
//        "properties": {"prop0": "value0"}

        $geoFormat = (object) [
            'type' => 'Feature',
            'geometry' => (object) [
                'type' => 'Point',
                'coordinates' => [(float) $place->getLongitude(),(float) $place->getLatitude()]
            ]
        ];


        return $geoFormat;
    }
}
