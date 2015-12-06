<?php

namespace AppBundle\Entity\Form;

class SearchProjections
{
    protected $movie;
    protected $genre;
    protected $cinema;
    protected $date;

    public function getMovie()
    {
        return $this->movie;
    }

    public function setMovie($movie)
    {
        $this->movie = $movie;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    public function getCinema()
    {
        return $this->cinema;
    }

    public function setCinema($cinema)
    {
        $this->cinema = $cinema;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date = null)
    {
        $this->date = $date;
    }
}
