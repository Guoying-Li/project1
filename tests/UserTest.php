<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

    public function testLogin($client, $email, $password): void
    {
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

      
       

        $client->submitForm('Sign in', [
            'email'=> $email,
            'password'=> $password
        ]);
        $this->assertResponseRedirects('/dashboard');
    }


    public function testRegister(): void
    {   
        $faker = Factory::create('fr_FR');

        $client = static::createClient();
        $client->request('GET', '/register');

         $this->assertResponseIsSuccessful();

        $email = $faker->email();
        $password = $faker->password();


        $client->submitForm('register_save', [
            'register[email]'=> $email,
            'register[firstname]' => $faker->firstName(),
            'register[lastname]' => $faker->lastName(),
            'register[password][first]' => $password,
            'register[password][second]' => $password,

        ]);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail($email);

        $this->assertNotNull($user);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();

        $client->submitForm('Sign in', [
            'email'=> $email,
            'password'=>$password
        ]);
        $this->assertResponseRedirects('/dashboard');
        
    }
}
