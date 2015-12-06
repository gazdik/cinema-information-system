<?php

namespace AppBundle\Entity\Form;

class Profit
{
    protected $valid;
    protected $movie;
    protected $cinemas;
    protected $value;

    public function getMovie()
    {
        return $this->movie;
    }

    public function setMovie($movie)
    {
        $this->movie = $movie;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    public function getCinemas()
    {
        return $this->cinemas;
    }

    public function setCinemas($cinemas)
    {
        $this->cinemas = $cinemas;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}
