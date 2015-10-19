<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MovieRepository")
 * @ORM\Table(name="movie")
 */
class Movie
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $name;
  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $year;
  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $length;

  /**
   * @ORM\ManyToOne(targetEntity="MovieGenre")
   * @ORM\JoinColumn(name="genre", referencedColumnName="genre", nullable=false)
   */
  protected $genre;

  /**
   * @ORM\OneToMany(targetEntity="Projection", mappedBy="movie")
   */
  protected $projections;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projections = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Movie
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
     * Set year
     *
     * @param integer $year
     *
     * @return Movie
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set length
     *
     * @param integer $length
     *
     * @return Movie
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set genre
     *
     * @param \AppBundle\Entity\MovieGenre $genre
     *
     * @return Movie
     */
    public function setGenre(\AppBundle\Entity\MovieGenre $genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return \AppBundle\Entity\MovieGenre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Add projection
     *
     * @param \AppBundle\Entity\Projection $projection
     *
     * @return Movie
     */
    public function addProjection(\AppBundle\Entity\Projection $projection)
    {
        $this->projections[] = $projection;

        return $this;
    }

    /**
     * Remove projection
     *
     * @param \AppBundle\Entity\Projection $projection
     */
    public function removeProjection(\AppBundle\Entity\Projection $projection)
    {
        $this->projections->removeElement($projection);
    }

    /**
     * Get projections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjections()
    {
        return $this->projections;
    }
}
