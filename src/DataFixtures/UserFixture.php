<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@example.com')
            ->setFirstname('Admin')
            ->setLastName('User')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'admin_password'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Création de 10 utilisateurs supplémentaires
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com")
                ->setFirstname("User{$i}")
                ->setLastName("LastName{$i}")
                ->setPassword($this->passwordHasher->hashPassword($user, "user_password{$i}"))
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['users'];
    }
}
