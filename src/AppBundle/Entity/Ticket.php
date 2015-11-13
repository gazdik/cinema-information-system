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
     * @ORM\Column(name="ticketPrice", type="decimal", precision=7, scale=2)
     */
    private $ticketPrice;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tickets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Projection", inversedBy="tickets")
     * @ORM\JoinColumn(name="projection_id", referencedColumnName="id", nullable=false)
     */
    private $projection;

    /**
     * @ORM\ManyToOne(targetEntity="Seat")
     * @ORM\JoinColumn(name="seat_id", referencedColumnName="id", nullable=true)
     */
    private $seat;

    /**
    *@ORM\ManyToOne(targetEntity="PriceCategory")
    *@ORM\JoinColumn(name="category_name", referencedColumnName="category", nullable=false)
    */
    private $priceCategory;


    public function s_booking_date()
    {
      return $this->booking_date->format('G:i');
    }

    public function s_payment_date()
    {
      return $this->payment_date->format('G:i');
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

    /**
     * Set ticketPrice
     *
     * @param string $ticketPrice
     *
     * @return Ticket
     */
    public function setTicketPrice($ticketPrice)
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    /**
     * Get ticketPrice
     *
     * @return string
     */
    public function getTicketPrice()
    {
        return $this->ticketPrice;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Ticket
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set priceCategory
     *
     * @param \AppBundle\Entity\PriceCategory $priceCategory
     *
     * @return Ticket
     */
    public function setPriceCategory(\AppBundle\Entity\PriceCategory $priceCategory)
    {
        $this->priceCategory = $priceCategory;

        return $this;
    }

    /**
     * Get priceCategory
     *
     * @return \AppBundle\Entity\PriceCategory
     */
    public function getPriceCategory()
    {
        return $this->priceCategory;
    }
}
