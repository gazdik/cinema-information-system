<?php

namespace AppBundle\Entity;

/**
 * ProjectionRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectionRepository extends \Doctrine\ORM\EntityRepository
{
    public function findFromToOrdered($date_from, $date_to)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
          ->from('AppBundle:Projection', 'p')
          ->where('p.date >= ?1')
            ->andWhere('p.date > CURRENT_DATE()')
            ->orWhere('p.date = CURRENT_DATE() AND p.end > CURRENT_TIME()' )
          ->setParameter(1, $date_from->format('Y-m-d'));

        if ($date_to) {
        $qb->andWhere('p.date <= ?2')
            ->setParameter(2, $date_to->format('Y-m-d'));

        }

        $qb->orderBy('p.date', 'ASC')
          ->addOrderBy('p.start', 'ASC');

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findAllOrdered()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from('AppBundle:Projection', 'p');

        $qb->orderBy('p.date', 'ASC');
        $qb->addOrderBy('p.start', 'ASC');

        $query = $qb->getQuery();

        return $query->getResult();

    }

    public function findByDateOrdered($date)
    {
        $query = $this->getEntityManager()->createQuery(
      'SELECT p
      FROM AppBundle:Projection p
      WHERE p.date = :date AND p.end > CURRENT_TIME()
      ORDER BY p.start ASC'
    )->setParameter('date', $date->format('Y-m-d'));

        return $query->getResult();
    }

    /**
     * Find by movie name, cinema, date of projection and movie genre.
     */
    public function awesomeFind($movie, $cinema, $date, $genre)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
          ->from('AppBundle:Projection', 'p')
          ->join('p.movie', 'm');

        if ($date) {
            $qb->andWhere('p.date = ?1')
                ->setParameter(1, $date->format('Y-m-d'));
        }

        if ($movie) {
            $qb->andWhere($qb->expr()->like('m.name', '?2'))
                ->setParameter(2, '%'.$movie.'%');
        }

        if ($genre) {
            $qb->join('m.genre', 'g')
                ->andWhere('g.genre = ?3')
                ->setParameter(3, $genre);
        }

        if ($cinema) {
            $qb->join('p.hall', 'h')
                ->join('h.cinema', 'c')
                ->andWhere('c.name = ?4')
                ->setParameter(4, $cinema);
        }

        $qb->orderBy('p.date', 'ASC');
        $qb->addOrderBy('p.start', 'ASC');

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
