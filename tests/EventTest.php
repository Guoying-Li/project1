<?php

namespace App\Tests;

use App\Repository\EventRepository;
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

    public function testCreateEvent(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jdoe@test.fr');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/event/create');
        $this->assertResponseIsSuccessful();
        $buttonCrawler = $crawler->selectButton('event_save');
        $form = $buttonCrawler->form();
        $faker = \Faker\Factory::create();
        $eventTitle = $faker->sentence(3);
        $eventDate = $faker->date('Y-m-d');
    
        $form['event[title]'] = $eventTitle;
        $form['event[date]'] = $eventDate;
        $form['event[is_private]'] = $faker->boolean();
    
        $client->submit($form);
    
        $this->assertTrue($client->getResponse()->isRedirect());
    
        $client->followRedirect();
    
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $event = $eventRepository->findOneBy([
            'title' => $eventTitle
        ]);
    
        $this->assertNotNull($event);
        $this->assertEquals($eventDate, $event->getDate()->format('Y-m-d'));
    }
    


}
