<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Place
 *
 * @ORM\Table(name="place")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaceRepository")
 * @ExclusionPolicy("all")
 */
class Place
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Expose
     */
    private $address;

    /**
     * @var int
     *
     * @ORM\Column(name="postalCode", type="smallint")
     * @Expose
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     * @Expose
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="siteweb", type="string", length=255, nullable=true)
     *
     */
    private $siteweb;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Expose
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Expose
     */
    private $longitude;

    /**
     *
     * @OneToMany(targetEntity="Image", mappedBy="place", cascade={"persist"})
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $images;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hasBuffet", type="boolean")
     */
    protected $hasBuffet;

    /**
     *
     * @ORM\Column(name="mondayStart", type="time", nullable=true)
     */
    protected $mondayStart;
    /**
     *
     * @ORM\Column(name="mondayStop", type="time", nullable=true)
     */
    protected $mondayStop;

    /**
     *
     * @ORM\Column(name="tuesdayStart", type="time", nullable=true)
     */
    protected $tuesdayStart;
    /**
     *
     * @ORM\Column(name="tuesdayStop", type="time", nullable=true)
     */
    protected $tuesdayStop;

    /**
     *
     * @ORM\Column(name="wednesdayStart", type="time", nullable=true)
     */
    protected $wednesdayStart;
    /**
     *
     * @ORM\Column(name="wednesdayStop", type="time", nullable=true)
     */
    protected $wednesdayStop;

    /**
     *
     * @ORM\Column(name="thursdayStart", type="time", nullable=true)
     */
    protected $thursdayStart;
    /**
     *
     * @ORM\Column(name="thursdayStop", type="time", nullable=true)
     */
    protected $thursdayStop;

    /**
     *
     * @ORM\Column(name="fridayStart", type="time", nullable=true)
     */
    protected $fridayStart;
    /**
     *
     * @ORM\Column(name="fridayStop", type="time", nullable=true)
     */
    protected $fridayStop;

    /**
     *
     * @ORM\Column(name="saturdayStart", type="time", nullable=true)
     */
    protected $saturdayStart;
    /**
     *
     * @ORM\Column(name="saturdayStop", type="time", nullable=true)
     */
    protected $saturdayStop;

    /**
     *
     * @ORM\Column(name="sundayStart", type="time", nullable=true)
     */
    protected $sundayStart;

    /**
     *
     * @ORM\Column(name="sundayStop", type="time", nullable=true)
     */
    protected $sundayStop;

    /**
     *
     * @ORM\Column(name="mondayStart", type="time", nullable=true)
     */
    protected $weekStart;
    /**
     *
     * @ORM\Column(name="mondayStop", type="time", nullable=true)
     */
    protected $weekStop;


    /**
     *
     * @ORM\Column(name="price", type="float")
     */
    protected $price;

    /**
     *
     * @ORM\Column(name="approved", type="boolean")
     * @Expose
     */
    protected $approved = false;



    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     *
     */
    protected $slug;

    /**
     * @var string
     * @Expose
     */
    protected $googleInfoBox;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->filters = new ArrayCollection();
        $this->updatedAt = new \DateTime("now");
        $this->createdAt = new \DateTime("now");
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = $this->getSlug();

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Place
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postalCode
     *
     * @param integer $postalCode
     *
     * @return Place
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return int
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set siteweb
     *
     * @param string $siteweb
     *
     * @return Place
     */
    public function setSiteweb($siteweb)
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    /**
     * Get siteweb
     *
     * @return string
     */
    public function getSiteweb()
    {
        return $this->siteweb;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Add placeImagess
     *
     * @param Image $image
     * @return Place
     */
    public function addImage(Image $image)
    {
        $image->addPlace($this);
        $this->images->add($image);
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    public function setImages($images){
        $this->images = $images;
    }

    public function getImages(){
        $images= new ArrayCollection();
        foreach($this->images as $image){
            if(!$image->getApproved()){
                continue;
            }
            $images->add($image);
        }
        return $images;
    }

    public function getAllImages(){
        $images= new ArrayCollection();
        foreach($this->images as $image){
            $images->add($image);
        }
        return $images;
    }

    public function getImageTeaser(){
        $image = $this->images->first();
        if(!$image){
            $image = new Image();
            $image->setImageName('defaultImage.jpeg');
        }
        return $image;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        if(!$this->slug) {
            $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
            $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
            $this->slug = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), str_replace($a, $b, $this->name)));
        }
        return $this->slug;
    }

    public function getGoogleAddress()
    {
        if(!$this->getAddress() && !$this->getPostalCode() && !$this->getCity()){
            return null;
        }
        return $this->getAddress() .', '. $this->getPostalCode() .'' . $this->getCity();
    }

    /**
     * @return boolean
     */
    public function isHasBuffet()
    {
        return $this->hasBuffet;
    }

    /**
     * @param boolean $hasBuffet
     */
    public function setHasBuffet($hasBuffet)
    {
        $this->hasBuffet = $hasBuffet;
    }

    /**
     * @return mixed
     */
    public function getMondayStart()
    {
        return $this->mondayStart;
    }

    /**
     * @param mixed $mondayStart
     */
    public function setMondayStart($mondayStart)
    {
        $this->mondayStart = $mondayStart;
    }

    /**
     * @return mixed
     */
    public function getMondayStop()
    {
        return $this->mondayStop;
    }

    /**
     * @param mixed $mondayStop
     */
    public function setMondayStop($mondayStop)
    {
        $this->mondayStop = $mondayStop;
    }

    /**
     * @return mixed
     */
    public function getTuesdayStart()
    {
        return $this->tuesdayStart;
    }

    /**
     * @param mixed $tuesdayStart
     */
    public function setTuesdayStart($tuesdayStart)
    {
        $this->tuesdayStart = $tuesdayStart;
    }

    /**
     * @return mixed
     */
    public function getTuesdayStop()
    {
        return $this->tuesdayStop;
    }

    /**
     * @param mixed $tuesdayStop
     */
    public function setTuesdayStop($tuesdayStop)
    {
        $this->tuesdayStop = $tuesdayStop;
    }

    /**
     * @return mixed
     */
    public function getWednesdayStart()
    {
        return $this->wednesdayStart;
    }

    /**
     * @param mixed $wednesdayStart
     */
    public function setWednesdayStart($wednesdayStart)
    {
        $this->wednesdayStart = $wednesdayStart;
    }

    /**
     * @return mixed
     */
    public function getWednesdayStop()
    {
        return $this->wednesdayStop;
    }

    /**
     * @param mixed $wednesdayStop
     */
    public function setWednesdayStop($wednesdayStop)
    {
        $this->wednesdayStop = $wednesdayStop;
    }

    /**
     * @return mixed
     */
    public function getThursdayStart()
    {
        return $this->thursdayStart;
    }

    /**
     * @param mixed $thursdayStart
     */
    public function setThursdayStart($thursdayStart)
    {
        $this->thursdayStart = $thursdayStart;
    }

    /**
     * @return mixed
     */
    public function getThursdayStop()
    {
        return $this->thursdayStop;
    }

    /**
     * @param mixed $thursdayStop
     */
    public function setThursdayStop($thursdayStop)
    {
        $this->thursdayStop = $thursdayStop;
    }

    /**
     * @return mixed
     */
    public function getFridayStart()
    {
        return $this->fridayStart;
    }

    /**
     * @param mixed $fridayStart
     */
    public function setFridayStart($fridayStart)
    {
        $this->fridayStart = $fridayStart;
    }

    /**
     * @return mixed
     */
    public function getFridayStop()
    {
        return $this->fridayStop;
    }

    /**
     * @param mixed $fridayStop
     */
    public function setFridayStop($fridayStop)
    {
        $this->fridayStop = $fridayStop;
    }

    /**
     * @return mixed
     */
    public function getSaturdayStart()
    {
        return $this->saturdayStart;
    }

    /**
     * @param mixed $saturdayStart
     */
    public function setSaturdayStart($saturdayStart)
    {
        $this->saturdayStart = $saturdayStart;
    }

    /**
     * @return mixed
     */
    public function getSaturdayStop()
    {
        return $this->saturdayStop;
    }

    /**
     * @param mixed $saturdayStop
     */
    public function setSaturdayStop($saturdayStop)
    {
        $this->saturdayStop = $saturdayStop;
    }

    /**
     * @return mixed
     */
    public function getSundayStart()
    {
        return $this->sundayStart;
    }

    /**
     * @param mixed $sundayStart
     */
    public function setSundayStart($sundayStart)
    {
        $this->sundayStart = $sundayStart;
    }

    /**
     * @return mixed
     */
    public function getSundayStop()
    {
        return $this->sundayStop;
    }

    /**
     * @param mixed $sundayStop
     */
    public function setSundayStop($sundayStop)
    {
        $this->sundayStop = $sundayStop;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getGoogleInfoBox()
    {
        return $this->googleInfoBox;
    }

    /**
     * @param string $googleInfoBox
     */
    public function setGoogleInfoBox($googleInfoBox)
    {
        $this->googleInfoBox = $googleInfoBox;
    }

    /**
     * @return mixed
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param mixed $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return mixed
     */
    public function getWeekStart()
    {
        return $this->weekStart;
    }

    /**
     * @param mixed $weekStart
     */
    public function setWeekStart($weekStart)
    {
        $this->weekStart = $weekStart;
    }

    /**
     * @return mixed
     */
    public function getWeekStop()
    {
        return $this->weekStop;
    }

    /**
     * @param mixed $weekStop
     */
    public function setWeekStop($weekStop)
    {
        $this->weekStop = $weekStop;
    }

    


    


}
