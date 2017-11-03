<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloControllerTest extends WebTestCase
{
    public function testShowPost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Hello Puzzle!")')->count()
        );
    }
}
