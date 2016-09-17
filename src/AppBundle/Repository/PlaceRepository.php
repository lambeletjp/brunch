<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Place;
use Geocoder\Model\AddressCollection;
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
        $googleAddress = $place->getGoogleAddress();
        if(!$googleAddress){
            return new AddressCollection();
        }
        return $this->getGoogleAddress($googleAddress);
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

    public function findPointAtDistanceInKm($latitude, $longitude, $distance)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $sql = 'SELECT id,
                ( 6371 * acos( cos( radians(:latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:longitude) ) + sin( radians(:latitude) ) * sin( radians( latitude ) ) ) ) AS distance 
                FROM place 
                HAVING distance < :distance 
                ORDER BY distance LIMIT 0 , 20;';
        $statement = $connection->prepare($sql);
        $statement->bindValue('longitude', $longitude);
        $statement->bindValue('latitude', $latitude);
        $statement->bindValue('distance', $distance);
        $statement->execute();
        $placesIds = $statement->fetchAll();

        $ids = [];
        foreach($placesIds as $placeId){
            $ids[] = $placeId['id'];
        }

        $places = [];
        if($ids){
            $ids = implode(',',$ids);
            $query = $em->createQuery('SELECT place FROM AppBundle:Place place WHERE place.id IN('.$ids.')');
            $places = $query->getResult();
        }

        return $places;
    }
}
