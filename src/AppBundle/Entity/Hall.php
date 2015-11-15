<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Seat;

/**
 * Hall
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Hall
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Cinema", inversedBy="halls")
     * @ORM\JoinColumn(name="cinema_name", referencedColumnName="name", nullable=false)
     */
    private $cinema;

    /**
     * @ORM\OneToMany(targetEntity="Seat", mappedBy="hall", cascade={"persist", "remove"})
     */
    private $seats;

    /**
     *
     */
    public function getCinemaAndName()
    {
        return $this->cinema->getName() . ': ' . $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seats = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Get id
     *
     * @return integer
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
     * @return Hall
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
     * Set cinema
     *
     * @param \AppBundle\Entity\Cinema $cinema
     *
     * @return Hall
     */
    public function setCinema(\AppBundle\Entity\Cinema $cinema)
    {
        $this->cinema = $cinema;

        return $this;
    }

    /**
     * Get cinema
     *
     * @return \AppBundle\Entity\Cinema
     */
    public function getCinema()
    {
        return $this->cinema;
    }

    /**
     * Add seat
     *
     * @param \AppBundle\Entity\Seat $seat
     *
     * @return Hall
     */
    public function addSeat(\AppBundle\Entity\Seat $seat)
    {
        $this->seats[] = $seat;

        return $this;
    }

    /**
     * Remove seat
     *
     * @param \AppBundle\Entity\Seat $seat
     */
    public function removeSeat(\AppBundle\Entity\Seat $seat)
    {
        $this->seats->removeElement($seat);
    }

    /**
     * Get seats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeats()
    {
        return $this->seats;
    }
}
