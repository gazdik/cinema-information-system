<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="Cinema", inversedBy="halls")
     * @ORM\JoinColumn(name="cinema_name", referencedColumnName="name", nullable=false)
     */
    private $cinema;

    /**
     * @ORM\OneToMany(targetEntity="Seat", mappedBy="hall")
     */
    private $seats;

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
