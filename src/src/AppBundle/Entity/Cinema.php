<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cinema
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cinema
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20)
     * @ORM\Id
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Hall", mappedBy="cinema", cascade={"persist", "remove"})
     */
    private $halls;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->halls = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Cinema
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return Cinema
     */
    public function setAddress($address = '')
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
     * Add hall
     *
     * @param \AppBundle\Entity\Hall $hall
     *
     * @return Cinema
     */
    public function addHall(\AppBundle\Entity\Hall $hall)
    {
        $this->halls[] = $hall;

        return $this;
    }

    /**
     * Remove hall
     *
     * @param \AppBundle\Entity\Hall $hall
     */
    public function removeHall(\AppBundle\Entity\Hall $hall)
    {
        $this->halls->removeElement($hall);
    }

    /**
     * Get halls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHalls()
    {
        return $this->halls;
    }
}
