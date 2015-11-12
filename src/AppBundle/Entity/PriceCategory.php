<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PriceCategory
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PriceCategory
{
    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string")
     * @ORM\Id
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="categoryPrice", type="decimal", precision=7, scale=2)
     */
    private $categoryPrice; 

    /**
     * Set category
     *
     * @param string $category
     *
     * @return PriceCategory
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set categoryPrice
     *
     * @param string $categoryPrice
     *
     * @return PriceCategory
     */
    public function setCategoryPrice($categoryPrice)
    {
        $this->categoryPrice = $categoryPrice;

        return $this;
    }

    /**
     * Get categoryPrice
     *
     * @return string
     */
    public function getCategoryPrice()
    {
        return $this->categoryPrice;
    }
}
