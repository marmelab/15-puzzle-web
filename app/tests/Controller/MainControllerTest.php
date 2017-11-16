<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase {
  public function testStart() {
    // @TODO: https://trello.com/c/jrYMP5ab
    $this->markTestSkipped();

    $client = static::createClient();

    $crawler = $client->request('GET', '/');

    $this->assertGreaterThan(
      0,
      $crawler->filter('html:contains("Welcome to the 15 puzzle game!")')->count()
    );
  }
}
