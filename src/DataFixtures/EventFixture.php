<?php

namespace App\DataFixtures;


use Faker\Factory;
use Faker\Generator;
use App\Entity\Event;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EventFixture extends Fixture
{
    // public const EVENT_IDS_REFERENCE = 'event_ids_reference';
    private Generator $faker;
    public function __construct()
    {
        $this ->faker =Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $ids = [];
        for($i=1; $i<10; $i++) {
            $event = new Event();
            $event ->setTitle($this ->faker ->sentence())
            -> setDate($this ->faker->dateTime());
            $manager->persist($event);
            $ids[] = $event->getId();
            

        }
     
        // $this->addReference(self::EVENT_IDS_REFERENCE, $ids);
        $manager->flush();
    }
}
