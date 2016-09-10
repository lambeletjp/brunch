<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlaceFilterPlace
 *
 * @ORM\Table(name="place_filter_place")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaceFilterPlaceRepository")
 */
class PlaceFilterPlace
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="placeFilterId", type="integer")
     */
    private $placeFilterId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;


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
     * Set placeFilterId
     *
     * @param integer $placeFilterId
     *
     * @return PlaceFilterPlace
     */
    public function setPlaceFilterId($placeFilterId)
    {
        $this->placeFilterId = $placeFilterId;

        return $this;
    }

    /**
     * Get placeFilterId
     *
     * @return int
     */
    public function getPlaceFilterId()
    {
        return $this->placeFilterId;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return PlaceFilterPlace
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

