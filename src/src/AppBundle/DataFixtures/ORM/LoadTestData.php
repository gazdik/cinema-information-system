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
use AppBundle\Entity\MovieGenre;
use AppBundle\Entity\PriceCategory;

// TODO: randomTimestamp
use AppBundle\DataFixtures\ORM\LoadTickets;


class LoadTestData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $cinemas = array(
array('name' => 'Lenore','address' => 'Ap #357-8948 Vel Avenue'),
array('name' => 'Lacy','address' => 'Ap #352-2380 Cum Road'),
array('name' => 'Nayda','address' => 'P.O. Box 165, 4449 Neque St.'),
array('name' => 'Penelope','address' => '293-2668 Pellentesque Avenue'),
array('name' => 'Fleur','address' => '682-300 Aliquam Rd.'),
array('name' => 'Catherine','address' => 'P.O. Box 594, 4921 Nibh. St.'),
array('name' => 'Margaret','address' => 'Ap #569-847 Vel Road'),
array('name' => 'Rinah','address' => '305-4594 Duis Rd.'),
array('name' => 'Emma','address' => '2238 Tristique Ave'),
array('name' => 'Adrienne','address' => 'P.O. Box 611, 989 Non, Rd.'),
array('name' => 'Wynter','address' => '846-7882 Cursus. St.'),
array('name' => 'Kyla','address' => '865-6179 Faucibus Road'),
array('name' => 'Zia','address' => '635-9553 Tempus Street'),
array('name' => 'Doris','address' => 'P.O. Box 646, 6803 Sed, Rd.'),
array('name' => 'Zenia','address' => '1288 Porttitor Av.'),
array('name' => 'Lara','address' => '810-3289 Facilisis, Road'),
array('name' => 'Gretchen','address' => 'Ap #443-1783 Cras Rd.'),
array('name' => 'Riley','address' => 'Ap #259-4314 Vel Rd.'),
array('name' => 'Idona','address' => '596-2340 Ipsum. Ave'),
array('name' => 'Lana','address' => '236-1067 Neque. Ave'),
array('name' => 'Marcia','address' => 'P.O. Box 825, 9659 Nunc Ave'),
array('name' => 'Hayfa','address' => '6192 Velit. Road'),
array('name' => 'Nola','address' => 'P.O. Box 890, 6463 Neque. Rd.'),
array('name' => 'Holly','address' => '9695 Sit Rd.'),
array('name' => 'Amelia','address' => '824-3134 Habitant Rd.'),
array('name' => 'Flavia','address' => 'Ap #243-1023 Nec, St.'),
);

private $genres = array(
'Horror',
'Comedy',
'Crime',
'Thriller',
'Drama',
'History',
'Film-Noir',
'Family',
'Animation',
'Romance',
);

private $movies = array(
    array('name' => 'The Shawshank Redemption','year' => 1994,'genre' => 'Horror','length' => 142),
    array('name' => 'Forrest Gump','year' => 1994,'genre' => 'Comedy','length' => 106),
    array('name' => 'The Green Mile','year' => 1999,'genre' => 'Crime','length' => 82),
    array('name' => 'One Flew Over the Cuckoo\'s Nest','year' => 1975,'genre' => 'Thriller','length' => 104),
    array('name' => 'Schindler\'s List','year' => 1993,'genre' => 'Drama','length' => 155),
    array('name' => 'Seven','year' => 1995,'genre' => 'History','length' => 244),
    array('name' => 'The Godfather','year' => 1972,'genre' => 'Film-Noir','length' => 286),
    array('name' => 'Intouchables','year' => 2011,'genre' => 'Family','length' => 265),
    array('name' => '12 Angry Men','year' => 1957,'genre' => 'Animation','length' => 197),
    array('name' => 'The Godfather: Part II','year' => 1974,'genre' => 'Romance','length' => 274),
    array('name' => 'Pelíšky','year' => 1999,'genre' => 'Horror','length' => 259),
    array('name' => 'Once Upon a Time in the West','year' => 1968,'genre' => 'Comedy','length' => 253),
    array('name' => 'Pulp Fiction','year' => 1994,'genre' => 'Crime','length' => 186),
    array('name' => 'Terminator 2: Judgment Day','year' => 1991,'genre' => 'Thriller','length' => 225),
    array('name' => 'The Silence of the Lambs','year' => 1991,'genre' => 'Drama','length' => 135),
    array('name' => 'The Lord of the Rings: The Fellowship of the Ring','year' => 2001,'genre' => 'History','length' => 160),
    array('name' => 'The Dark Knight','year' => 2008,'genre' => 'Film-Noir','length' => 131),
    array('name' => 'The Good, the Bad and the Ugly','year' => 1966,'genre' => 'Family','length' => 142),
    array('name' => 'The Matrix','year' => 1999,'genre' => 'Animation','length' => 182),
    array('name' => 'Rain Man','year' => 1988,'genre' => 'Romance','length' => 183),
    array('name' => 'Raiders of the Lost Ark','year' => 1981,'genre' => 'Horror','length' => 239),
    array('name' => 'The Magnificent Seven','year' => 1960,'genre' => 'Comedy','length' => 281),
    array('name' => 'Earth','year' => 2007,'genre' => 'Crime','length' => 192),
    array('name' => 'The Sting','year' => 1973,'genre' => 'Thriller','length' => 107),
    array('name' => 'Aliens','year' => 1986,'genre' => 'Drama','length' => 140),
    array('name' => 'Alien','year' => 1979,'genre' => 'History','length' => 256),
    array('name' => 'Higher Principle','year' => 1960,'genre' => 'Film-Noir','length' => 93),
    array('name' => 'Indiana Jones and the Last Crusade','year' => 1989,'genre' => 'Family','length' => 237),
    array('name' => 'Die Hard','year' => 1988,'genre' => 'Animation','length' => 283),
    array('name' => 'Leon','year' => 1994,'genre' => 'Romance','length' => 151),
    array('name' => 'Butch Cassidy and the Sundance Kid','year' => 1969,'genre' => 'Horror','length' => 260),
    array('name' => 'L. A. Confidential','year' => 1997,'genre' => 'Comedy','length' => 206),
    array('name' => 'Cesta do hlubin študákovy duše','year' => 1939,'genre' => 'Crime','length' => 252),
    array('name' => 'City of God','year' => 2002,'genre' => 'Thriller','length' => 106),
    array('name' => 'Amadeus','year' => 1984,'genre' => 'Drama','length' => 160),
    array('name' => 'Star Wars: Episode V - The Empire Strikes Back','year' => 1980,'genre' => 'History','length' => 272),
    array('name' => 'Obecná škola','year' => 1991,'genre' => 'Film-Noir','length' => 146),
    array('name' => 'Spalovač mrtvol','year' => 1968,'genre' => 'Family','length' => 163),
    array('name' => '„Marečku, podejte mi pero!“','year' => 1976,'genre' => 'Animation','length' => 211),
    array('name' => 'S čerty nejsou žerty','year' => 1984,'genre' => 'Romance','length' => 94),
    array('name' => 'Saving Private Ryan','year' => 1998,'genre' => 'Horror','length' => 136),
    array('name' => 'A Beautiful Mind','year' => 2001,'genre' => 'Comedy','length' => 280),
    array('name' => 'Dances with Wolves','year' => 1990,'genre' => 'Crime','length' => 148),
    array('name' => 'Inception','year' => 2010,'genre' => 'Thriller','length' => 134),
    array('name' => 'Casino','year' => 1995,'genre' => 'Drama','length' => 158),
    array('name' => 'Back to the Future','year' => 1985,'genre' => 'History','length' => 104),
    array('name' => 'The Shining','year' => 1980,'genre' => 'Film-Noir','length' => 164),
    array('name' => 'Heat','year' => 1995,'genre' => 'Family','length' => 110),
    array('name' => 'Vesničko má středisková','year' => 1985,'genre' => 'Animation','length' => 158),
    array('name' => 'The Pianist','year' => 2002,'genre' => 'Romance','length' => 190),
    array('name' => 'American History X','year' => 1998,'genre' => 'Horror','length' => 194),
    array('name' => 'Life Is Beautiful','year' => 1997,'genre' => 'Comedy','length' => 98),
    array('name' => 'The Usual Suspects','year' => 1995,'genre' => 'Crime','length' => 191),
    array('name' => 'Gladiator','year' => 2000,'genre' => 'Thriller','length' => 259),
    array('name' => 'Fight Club','year' => 1999,'genre' => 'Drama','length' => 186),
    array('name' => 'The Sixth Sense','year' => 1999,'genre' => 'History','length' => 283),
    array('name' => 'Star Wars: Episode IV - A New Hope','year' => 1977,'genre' => 'Film-Noir','length' => 217),
    array('name' => 'Platoon','year' => 1986,'genre' => 'Family','length' => 105),
    array('name' => 'Once Upon a Time in America','year' => 1984,'genre' => 'Animation','length' => 285),
    array('name' => 'The Lord of the Rings: The Two Towers','year' => 2002,'genre' => 'Romance','length' => 239),
    array('name' => 'Million Dollar Baby','year' => 2004,'genre' => 'Horror','length' => 98),
    array('name' => 'Lethal Weapon','year' => 1987,'genre' => 'Comedy','length' => 272),
    array('name' => 'Senna','year' => 2010,'genre' => 'Crime','length' => 138),
    array('name' => 'Psycho','year' => 1960,'genre' => 'Thriller','length' => 281),
    array('name' => 'Roman Holiday','year' => 1953,'genre' => 'Drama','length' => 196),
    array('name' => 'Papillon','year' => 1973,'genre' => 'History','length' => 265),
    array('name' => 'Braveheart','year' => 1995,'genre' => 'Film-Noir','length' => 203),
    array('name' => 'The Prestige','year' => 2006,'genre' => 'Family','length' => 195),
    array('name' => 'Some Like It Hot','year' => 1959,'genre' => 'Animation','length' => 140),
    array('name' => 'The Twelve Tasks of Asterix','year' => 1976,'genre' => 'Romance','length' => 219),
    array('name' => 'U pokladny stál...','year' => 1943,'genre' => 'Horror','length' => 81),
    array('name' => 'Warrior','year' => 2011,'genre' => 'Comedy','length' => 257),
    array('name' => 'Good Will Hunting','year' => 1997,'genre' => 'Crime','length' => 193),
    array('name' => 'Requiem for a Dream','year' => 2000,'genre' => 'Thriller','length' => 141),
    array('name' => 'Witches\' Hammer','year' => 1969,'genre' => 'Drama','length' => 149),
    array('name' => 'Django Unchained','year' => 2012,'genre' => 'History','length' => 172),
    array('name' => 'The Bourne Ultimatum','year' => 2007,'genre' => 'Film-Noir','length' => 290),
    array('name' => 'Goodfellas','year' => 1990,'genre' => 'Family','length' => 174),
    array('name' => 'Shutter Island','year' => 2010,'genre' => 'Animation','length' => 189),
    array('name' => 'Snatch.','year' => 2000,'genre' => 'Romance','length' => 274),
    array('name' => 'Cool Hand Luke','year' => 1967,'genre' => 'Horror','length' => 124),
    array('name' => 'American Beauty','year' => 1999,'genre' => 'Comedy','length' => 175),
    array('name' => 'Na samotě u lesa','year' => 1976,'genre' => 'Crime','length' => 198),
    array('name' => 'Star Wars: Episode III - Revenge of the Sith','year' => 2005,'genre' => 'Thriller','length' => 257),
    array('name' => 'Cesta do pravěku','year' => 1955,'genre' => 'Drama','length' => 211),
    array('name' => 'Trainspotting','year' => 1996,'genre' => 'History','length' => 164),
    array('name' => 'Star Wars: Episode VI - Return of the Jedi','year' => 1983,'genre' => 'Film-Noir','length' => 213),
    array('name' => 'Seven Samurai','year' => 1954,'genre' => 'Family','length' => 295),
    array('name' => 'The Last Boy Scout','year' => 1991,'genre' => 'Animation','length' => 172),
    array('name' => 'The Hunt','year' => 2012,'genre' => 'Romance','length' => 249),
    array('name' => 'Awakenings','year' => 1990,'genre' => 'Horror','length' => 165),
    array('name' => 'The Bear','year' => 1988,'genre' => 'Comedy','length' => 256),
    array('name' => 'The Butterfly Effect','year' => 2004,'genre' => 'Crime','length' => 261),
    array('name' => 'American Gangster','year' => 2007,'genre' => 'Thriller','length' => 105),
    array('name' => 'Jára Cimrman ležící, spící','year' => 1983,'genre' => 'Drama','length' => 182),
    array('name' => 'Lock, Stock and Two Smoking Barrels','year' => 1998,'genre' => 'History','length' => 81),
    array('name' => 'Shrek','year' => 2001,'genre' => 'Film-Noir','length' => 127),
);

private $discount = array(
  "student",
  "senior",
  "baby",
  "employee"
);

private $priceCategories = array(
  "adult" => 5,
  "student" => 4,
  "senior" => 3,
  "baby" => 2,
  "employee" => 1
);

    private $users = array(
        array('name' => 'John Maria Doe', 'email' => 'admin@mail.com', 'pass' => 'pass', 'role' => 'ROLE_ADMIN', 'active' => true),
        array('name' => 'John Hernandez Doe', 'email' => 'manager@mail.com', 'pass' => 'pass', 'role' => 'ROLE_MANAGER', 'active' => true),
        array('name' => 'John Vieira Doe', 'email' => 'cashier@mail.com', 'pass' => 'pass', 'role' => 'ROLE_CASHIER', 'active' => true),
        array('name' => 'John Sanchez Doe', 'email' => 'user@mail.com', 'pass' => 'pass', 'role' => 'ROLE_USER', 'active' => true),
        array('name' => 'John Velazques Doe', 'email' => 'blockedUser@mail.com', 'pass' => 'pass', 'role' => 'ROLE_USER', 'active' => false),
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

        /**
         * Add users
         */
        foreach ($this->users as $user) {
            $newUser = $userManager->createUser();
            $newUser->setUsername($user['email']);
            $newUser->setEmail($user['email']);
            $newUser->setName($user['name']);
            $newUser->setPlainPassword($user['pass']);
            $newUser->setEnabled($user['active']);
            $newUser->setRoles(array($user['role']));

            $userManager->updateUser($newUser, true);
        }

      /**
      *Add price categories into database
      */
      foreach ($this->priceCategories as $category => $price) {
        $priceCategory = new priceCategory();
        $priceCategory->setCategory($category);
        $priceCategory->setCategoryPrice($price);

        $manager->persist($priceCategory);
      }
      $manager->flush();



      /**
       * Add clients into database
       */
/*
      foreach ($this->clients as $i) {
          $client = new Client();
          $client->setEmail($i['email']);
          $client->setFirstname($i['firstname']);
          $client->setSurname($i['surname']);
          $client->setAddress($i['address']);

          $manager->persist($client);
      }
        $manager->flush();
*/
      /**
       * Add cinemas, halls and seats into database
       */
      foreach ($this->cinemas as $foo) {
          $cinema = new Cinema();
          $cinema->setName($foo['name']);
          $cinema->setAddress($foo['address']);

          for ($i = 0; $i < rand(1, 3); ++$i) {
              $hall = new Hall();
              $hall->setName($i);
              $hall->setCinema($cinema);

              for ($j = 0; $j < rand(100, 500); ++$j) {
                  $seat = new Seat();

                  $seat->setNumber($j + 1);
                  $seat->setHall($hall);

                  $manager->persist($seat);
              }
              $manager->persist($hall);
          }
          $manager->persist($cinema);
      }
      $manager->flush();

      /**
       * Add genres into database
       */
      foreach ($this->genres as $foo) {
        $genre = new MovieGenre();
        $genre->setGenre($foo);
        $manager->persist($genre);
      }
      $manager->flush();

      // Fetch genres from DB
      $repository = $em->getRepository('AppBundle:MovieGenre');
      $genres = $repository->findAll();

      /**
       * Add movies into database
       */
      foreach ($this->movies as $foo) {
        $movie = new Movie();
        $movie->setName($foo['name']);
        $movie->setYear($foo['year']);

        $genre = $genres[array_rand($genres)];
        $movie->setGenre($genre);

        $movie->setLength($foo['length']);

        $manager->persist($movie);
      }
      $manager->flush();

      /**
       * Add tickets and projections
       */
      // Fetch clients, seats and movies from database

      $repository = $em->getRepository('AppBundle:Client');
      $clients = $repository->findAll();
      $repository = $em->getRepository('AppBundle:Movie');
      $movies = $repository->findAll();
      $repository = $em->getRepository('AppBundle:Hall');
      $halls = $repository->findAll();


      // Add projections into database
      for ($i = 0; $i < 600; $i++) {
        $projection = new Projection();
        $movie = $movies[array_rand($movies)];
        $hall = $halls[array_rand($halls)];

        $date = new \DateTime();
        $date2 = new \DateTime();

        // Set attributes to projection
        $timestamp = randomTimestamp('2015/12/01', '2016/01/31');
        $date->setTimestamp($timestamp);
        $projection->setDate($date);
        $projection->setStart($date);
        $date2->setTimestamp($timestamp + $movie->getLength()*60);
        $projection->setEnd($date2);

        $projection->setMovie($movie);
        $projection->setHall($hall);

        $manager->persist($projection);
      }
      $manager->flush();
    }

    /**
  * {@inheritDoc}
  */
 public function getOrder()
 {
     return 1; // the order in which fixtures will be loaded
 }
}
