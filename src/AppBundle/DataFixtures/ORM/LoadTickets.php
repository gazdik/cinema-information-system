<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\Client;
use AppBundle\Entity\Cinema;
use AppBundle\Entity\Hall;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Seat;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Projection;

function randomTimestamp($start_date, $end_date)
{
    // Convert to timetamps
    $min = strtotime($start_date);
    $max = strtotime($end_date);

    // Generate random number using above bounds
    return rand($min, $max);


}

class LoadTickets extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

private $discount = array(
  "student",
  "senior",
  "baby",
  "employee",
  "dog",
);

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
      $em = $this->container->get('doctrine')->getEntityManager('default');

      // Get projections and clients from database
      $repository = $em->getRepository('AppBundle:Projection');
      $projections = $repository->findAll();
      $repository = $em->getRepository('AppBundle:Client');
      $clients = $repository->findAll();

      // Add tickets into database
      for($i = 0; $i < 10000; $i++) {
        $ticket = new Ticket();
        $projection = $projections[array_rand($projections)];
        $seats = $projection->getHall()->getSeats()->getValues();
        $seat = $seats[array_rand($seats)];

        // Set attributes to ticket
        if (rand(0,1) == 1) {
          $ticket->setClient($clients[array_rand($clients)]);
        }

        $ticket->setProjection($projection);
        $ticket->setSeat($seat);
        $ticket->setPrice(rand(50,150));
        if (rand(0,1) == 1) {
          $ticket->setDiscount($this->discount[rand(0,3)]);
        }

        // Set dates
        $date = new \DateTime();
        $date->setTimestamp(randomTimestamp('2015/10/01', '2015/11/30'));
        if (rand(0,1) == 1) {
          $ticket->setBookingDate($date);
        }
        $date = new \DateTime();
        $date->setTimestamp(randomTimestamp('2015/10/01', '2015/11/30'));
        if (rand(0,1) == 1 || !$ticket->getBookingDate()) {
          $ticket->setPaymentDate($date);
        }

        $manager->persist($ticket);
      }
      $manager->flush();
    }

    /**
  * {@inheritDoc}
  */
 public function getOrder()
 {
     return 2; // the order in which fixtures will be loaded
 }
}
