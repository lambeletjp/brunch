<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Doctrine\ORM\Mapping\OneToMany;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Place
 *
 * @ORM\Table(name="place")
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 * @ExclusionPolicy("all")
 */
class Place
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     * @Expose
     */
    private $address;

    /**
     * @var int
     *
     * @ORM\Column(name="postalCode", type="smallint", nullable=false)
     * @Expose
     */
    private $postalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     * @Expose
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(name="siteweb", type="string", length=255, nullable=true)
     * @Expose
     */
    private $siteweb;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     * @Expose
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
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
     * @var string|null
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="hasBuffet", type="boolean", nullable=true)
     */
    private $hasbuffet;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="saturdayStart", type="time", nullable=true)
     */
    private $saturdaystart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="saturdayStop", type="time", nullable=true)
     */
    private $saturdaystop;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="sundayStart", type="time", nullable=true)
     */
    private $sundaystart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="sundayStop", type="time", nullable=true)
     */
    private $sundaystop;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    private $approved = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="weekStart", type="time", nullable=true)
     */
    private $weekstart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="weekStop", type="time", nullable=true)
     */
    private $weekstop;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var float|null
     *
     * @ORM\Column(name="priceStart", type="float", precision=10, scale=0, nullable=true)
     */
    private $pricestart;

    /**
     * @var float|null
     *
     * @ORM\Column(name="priceEnd", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceend;

    /**
     * @var string
     * @Expose
     */
    private $googleInfoBox;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalcode(): ?int
    {
        return $this->postalcode;
    }

    public function setPostalcode(int $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSiteweb(): ?string
    {
        return $this->siteweb;
    }

    public function setSiteweb(?string $siteweb): self
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
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

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getHasbuffet(): ?bool
    {
        return $this->hasbuffet;
    }

    public function setHasbuffet(?bool $hasbuffet): self
    {
        $this->hasbuffet = $hasbuffet;

        return $this;
    }

    public function getSaturdaystart(): ?\DateTimeInterface
    {
        return $this->saturdaystart;
    }

    public function setSaturdaystart(?\DateTimeInterface $saturdaystart): self
    {
        $this->saturdaystart = $saturdaystart;

        return $this;
    }

    public function getSaturdaystop(): ?\DateTimeInterface
    {
        return $this->saturdaystop;
    }

    public function setSaturdaystop(?\DateTimeInterface $saturdaystop): self
    {
        $this->saturdaystop = $saturdaystop;

        return $this;
    }

    public function getSundaystart(): ?\DateTimeInterface
    {
        return $this->sundaystart;
    }

    public function setSundaystart(?\DateTimeInterface $sundaystart): self
    {
        $this->sundaystart = $sundaystart;

        return $this;
    }

    public function getSundaystop(): ?\DateTimeInterface
    {
        return $this->sundaystop;
    }

    public function setSundaystop(?\DateTimeInterface $sundaystop): self
    {
        $this->sundaystop = $sundaystop;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function isApproved(): bool
    {
        return !!$this->getApproved();
    }

    public function getWeekstart(): ?\DateTimeInterface
    {
        return $this->weekstart;
    }

    public function setWeekstart(?\DateTimeInterface $weekstart): self
    {
        $this->weekstart = $weekstart;

        return $this;
    }

    public function getWeekstop(): ?\DateTimeInterface
    {
        return $this->weekstop;
    }

    public function setWeekstop(?\DateTimeInterface $weekstop): self
    {
        $this->weekstop = $weekstop;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPricestart(): ?float
    {
        return $this->pricestart;
    }

    public function setPricestart(?float $pricestart): self
    {
        $this->pricestart = $pricestart;

        return $this;
    }

    public function getPriceend(): ?float
    {
        return $this->priceend;
    }

    public function setPriceend(?float $priceend): self
    {
        $this->priceend = $priceend;

        return $this;
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
     * @param string $googleInfoBox
     */
    public function setGoogleInfoBox($googleInfoBox)
    {
        $this->googleInfoBox = $googleInfoBox;
    }

    /**
     * @return string
     */
    public function getGoogleInfoBox()
    {
        return $this->googleInfoBox;
    }




}
