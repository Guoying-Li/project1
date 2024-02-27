<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Picture;
use App\DataFixtures\EventFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
     private Generator $faker;
     public static function getGroups(): array {
        return [
            'groupe1'
        ];
     }
     public function __construct(UserPasswordHasherInterface $hasher)
     {
        //   $this ->faker =Factory::create('fr_FR');
         $this->hasher = $hasher;
     }
    // public function load(ObjectManager $manager): void
    // {
    //     for($i = 0; $i<50; $i++) {

    //         $picture = new Picture();
    //         $picture->setFilename("https://picsum.photos/seed/". $this ->faker->word() . "/200/100")
    //                -> setDate($this ->faker->dateTime())
    //                ->setPlace($this ->faker->city());
    //         $manager->persist($picture);
   
          

    //     }
    //     // $eventIds = $this -> getReference(EventFixture:: EVENT_IDS_REFERENCE);
    //     $manager->flush();
    // }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('admin');
        $user->setEmail('test@gmail.com');
        $user->setLastname('doeDoe');

        $password = $this->hasher->hashPassword($user, 'pass_1234');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EventFixture::class
        ];
    }
}
