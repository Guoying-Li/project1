<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Picture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PictureFixture extends Fixture
{

    // private Generator $faker;
    // public function __construct()
    // {
    //     $this ->faker =Factory::create('fr_Fr');
    // }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i<50; $i++) {

            $picture = new Picture();
            $picture->setFilename("https://picsum.photos/200/300")
                   -> setDate(new DateTime())
                   ->setPlace('Strasbourg');
            $manager->persist($picture);
   
          

        }
        $manager->flush();
    }
}
