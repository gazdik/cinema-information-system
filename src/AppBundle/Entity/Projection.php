<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Projection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ProjectionRepository")
 */
class Projection
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
     * @ORM\Column(name="date", type="date")
     * @var \DateTime
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="time")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="time", nullable=true)
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="projections")
     * @ORM\JoinColumn(name="movie_id", nullable=FALSE, referencedColumnName="id")
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="Hall")
     * @ORM\JoinColumn(name="hall_id", nullable=FALSE, referencedColumnName="id")
     */
    private $hall;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="projection")
     */
    private $tickets;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function s_start()
    {
      return $this->start->format('G:i');
    }

    public function s_end()
    {
      return $this->start->format('G:i');
    }
    public function s_date()
    {
      return $this->date->format('j.n.Y');
    }

    public function getHallName()
    {
      return $this->hall->getName();
    }

    public function getCinemaName()
    {
      return $this->hall->getCinema()->getName();
    }

    public function getMovieName()
    {
      return $this->movie->getName();
    }

    public function getGenre()
    {
        if ($this->movie->getGenre()) {
            return $this->movie->getGenre()->getGenre();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Projection
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Projection
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Projection
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return Projection
     */
    public function setMovie(\AppBundle\Entity\Movie $movie)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \AppBundle\Entity\Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set hall
     *
     * @param \AppBundle\Entity\Hall $hall
     *
     * @return Projection
     */
    public function setHall(\AppBundle\Entity\Hall $hall)
    {
        $this->hall = $hall;

        return $this;
    }

    /**
     * Get hall
     *
     * @return \AppBundle\Entity\Hall
     */
    public function getHall()
    {
        return $this->hall;
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Projection
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
