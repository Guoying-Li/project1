<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Picture;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PictureFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUMBER_MAX_OF_PICTURES = 30;
    private Generator $faker;
    private EventRepository $eventRepository;
    private UserRepository $userRepository;

    public static function getGroups(): array {
        return [
            'group1'
        ];
    }

    public function __construct(EventRepository $eventRepository, UserRepository $userRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this-> userRepository->findAll();
        foreach ($users as $user) {
            $nbrPictures = $this->faker->numberBetween(1, self::NUMBER_MAX_OF_PICTURES);
            for ($i = 0; $i < $nbrPictures; $i++) {

                $picture = new Picture();

                $picture->setFilename("https://picsum.photos/seed/" . $this->faker->word() . "/200/100")
                    ->setDate($this->faker->dateTime())
                    ->setPlace($this->faker->city())
                    ->setCreatedBy($user)
                    ->setIsPublic($this->faker->boolean());

                $manager->persist($picture);
            }
    
        }
       
        //$eventIds = $this->getReference(EventFixture::EVENT_IDS_REFERENCE); < ne fonctionne qu'avec des objets

        $events = $this->eventRepository->findAll();
        foreach($events as $e) {

            $nbrPictures = $this->faker->numberBetween(1, 10);
            for($i = 0; $i < $nbrPictures; $i++) {
                $picture = new Picture();

                $picture->setFilename("https://picsum.photos/seed/" . $this->faker->word() . "/200/100")
                    ->setDate($this->faker->dateTime())
                    ->setPlace($this->faker->city())
                    ->setEvent($e)
                    ->setCreatedBy($e->getCreatedBy());
                
                $manager->persist($picture);
            }       
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EventFixture::class
        ];
    }
}
