<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieGenre
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MovieGenre
{
    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string")
     * @ORM\Id
     */
    private $genre;


    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return MovieGenre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }
}
