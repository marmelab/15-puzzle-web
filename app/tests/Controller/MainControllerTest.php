<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase {
    public function testStart() {
      $client = static::createClient();

      $crawler = $client->request('GET', '/');

      $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Welcome to the 15 puzzle game!")')->count()
      );
    }
}
