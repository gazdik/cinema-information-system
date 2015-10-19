<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Ticket
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
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=7, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="string", length=20, nullable=true)
     */
    private $discount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="booking_date", type="datetime", nullable=true)
     */
    private $booking_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_date", type="datetime", nullable=true)
     */
    private $payment_date;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="tickets")
     * @ORM\JoinColumn(name="client_email", referencedColumnName="email", nullable=true)
     */
    private $client;


    /**
     * @ORM\ManyToOne(targetEntity="Projection", inversedBy="tickets")
     * @ORM\JoinColumn(name="projection_id", referencedColumnName="id", nullable=false)
     */
    private $projection;

    /**
     * @ORM\ManyToOne(targetEntity="Seat")
     * @ORM\JoinColumn(name="seat_id", referencedColumnName="id", nullable=false)
     */
    private $seat;


    public function s_booking_date()
    {
      return $this->booking_date->format('G:i');
    }

    public function s_payment_date()
    {
      return $this->booking_date->format('G:i');
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
     * Set price
     *
     * @param string $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set discount
     *
     * @param string $discount
     *
     * @return Ticket
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return Ticket
     */
    public function setBookingDate($bookingDate)
    {
        $this->booking_date = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->booking_date;
    }

    /**
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     *
     * @return Ticket
     */
    public function setPaymentDate($paymentDate)
    {
        $this->payment_date = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->payment_date;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Ticket
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set projection
     *
     * @param \AppBundle\Entity\Projection $projection
     *
     * @return Ticket
     */
    public function setProjection(\AppBundle\Entity\Projection $projection)
    {
        $this->projection = $projection;

        return $this;
    }

    /**
     * Get projection
     *
     * @return \AppBundle\Entity\Projection
     */
    public function getProjection()
    {
        return $this->projection;
    }

    /**
     * Set seat
     *
     * @param \AppBundle\Entity\Seat $seat
     *
     * @return Ticket
     */
    public function setSeat(\AppBundle\Entity\Seat $seat)
    {
        $this->seat = $seat;

        return $this;
    }

    /**
     * Get seat
     *
     * @return \AppBundle\Entity\Seat
     */
    public function getSeat()
    {
        return $this->seat;
    }
}
