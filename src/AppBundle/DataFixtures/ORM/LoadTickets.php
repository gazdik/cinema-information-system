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
    //These users will be the tickets sold to
    private $userEmails = array(
        'user@email.com',
        'blockedUser@email.com',
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
        $userManager = $this->container->get('fos_user.user_manager');
        $date = new \DateTime();

        // Get projections and clients from database
        $projections = $em->getRepository('AppBundle:Projection')->findAll();

        //Get price categories
        $priceCategories = $em->getRepository('AppBundle:PriceCategory')->findAll();

        //Get users
        $users = array();

        foreach ($this->userEmails as $email) {
            $users[] = $userManager->findUserByEmail('user@mail.com');
        }

      // Add tickets into database
      for($i = 0; $i < 10000; $i++) {
          $ticket = new Ticket();
          $projection = $projections[array_rand($projections)];
          $priceCategory = $priceCategories[array_rand($priceCategories)];

          $freeSeats = $em->getRepository('AppBundle:Seat')
              ->findFreeSeats($projection->getId());
          $seat = $freeSeats[array_rand($freeSeats)];

            // Set attributes to ticket
            if (rand(0,1) == 1)
              $ticket->setUser($users[array_rand($users)]);

            $ticket->setProjection($projection);
            $ticket->setSeat($seat);
            $ticket->setPriceCategory($priceCategory);
            $ticket->setTicketPrice($priceCategory->getCategoryPrice());

            $date->setTimestamp(randomTimestamp('2015/10/01', $projection->getDate()->format('Y-m-d')));
            $ticket->setBookingDate($date);

            $date->setTimestamp(randomTimestamp($date->format('Y-m-d'), $projection->getDate()->format('Y-m-d')));
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
