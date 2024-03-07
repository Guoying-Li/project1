<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello, world!');
    }
    public function testRedirectToLogin(): void
    {

        $client = static::createClient();

        $url = [
            '/event', '/event/all', '/event/shared', '/event/1'
        ];
        foreach($url as $u) {
        $crawler = $client->request('GET', $u);
        $this->assertResponseRedirects('http://localhost/login');
        }
    }

    public function testAllowConnected():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('sun@test.com');

        $client->loginUser($testUser);
        $client->request('GET', '/event');

        $this->assertResponseIsSuccessful();
    }




}
