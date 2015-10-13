<?php

namespace AppBundle\Entity;

/**
 * ProjectionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectionRepository extends \Doctrine\ORM\EntityRepository
{
  public function findByDateOrdered ($date)
  {
    $query = $this->getEntityManager()->createQuery(
      'SELECT p
      FROM AppBundle:Projection p
      WHERE p.date = :date
      ORDER BY p.start ASC'
    )->setParameter('date', $date->format('Y-m-d'));

    return $query->getResult();
  }
}
