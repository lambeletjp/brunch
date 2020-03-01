<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Geocoder\Model\AddressCollection;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Query\GeocodeQuery;
use Geocoder\StatefulGeocoder as StatefulGeocoder;
use Http\Adapter\Guzzle6\Client as Client;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    protected $apiKey;

    /**
     * PlaceRepository constructor.
     * @param ManagerRegistry $registry
     * @param string $googleApiKey
     */
    public function __construct(
        ManagerRegistry $registry,
        string $googleApiKey
    )
    {
        $this->apiKey = $googleApiKey;
        parent::__construct($registry, Place::class);
    }

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
        try {
            $httpClient = new Client();
            $provider = new GoogleMaps($httpClient, null, $this->apiKey);
            $geocoder = new StatefulGeocoder($provider, 'en');
            $result = $geocoder->geocodeQuery(GeocodeQuery::create($address));
            return $result;
        }catch (\Exception $e){
            return new ArrayCollection();
        }
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
            $query = $em->createQuery('SELECT place FROM App:Place place WHERE place.id IN('.$ids.')');
            $places = $query->getResult();
        }

        return $places;
    }
}
