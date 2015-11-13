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
     * @ORM\Column(name="number", type="integer")
     * @var integer
     */
    private $number;

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
     * Constructor
     */
    public function __construct()
    {
        $this->seats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Return capacity
     *
     * @return integer
     */
    public function getCapacity()
    {
        return $this->seats->count();
    }

    /**
     * Set capacity
     * @param integer $capacity
     */
    public function setCapacity($capacity)
    {
        $this->seats->clear();

        for ($i = 1; $i <= $capacity; $i++) {
            $seat = new Seat();
            $seat->setHall($this);
            $seat->setNumber($i);

            $this->seats->add($seat);
        }
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

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Hall
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }
}
