<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use App\Api\GameApi;
use App\Entity\Game;
use App\Api\TokenGenerator;

class GameApiTest extends TestCase {
  private function createGuzzleHttpMock($body, $status, $headers) {
    $mock = new MockHandler([
      new Response($status, $headers, $body)
    ]);
  
    $handler = HandlerStack::create($mock);
    return new Client(['handler' => $handler]);
  }

  private function createGameApi($body) {
    $bodyJson = json_encode($body);
    $client = $this->createGuzzleHttpMock($bodyJson, 200, ['Content-Type' => 'application/json']);
    $encoders = array(new XmlEncoder(), new JsonEncoder());
    $normalizers = array(new ObjectNormalizer());    
    $serializer = new Serializer($normalizers, $encoders);
    $tokenGeneratorStub = $this->createMock(TokenGenerator::class);
    $tokenGeneratorStub->method('generate')->willReturn('mockedToken');

    return new GameApi($client, $serializer, $tokenGeneratorStub);
  }

  public function testNew() {
    
    $mockedResponse = [
      'InitialGrid' => array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      'Grid' => array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      )
    ];

    $gameApi = $this->createGameApi($mockedResponse);

    $game = $gameApi->new(3);

    $expectedGame = new Game(
      'mockedToken',
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      )
    );

    $this->assertEquals($game->getToken(), $expectedGame->getToken());
    $this->assertEquals($game->getResolvedGrid(), $expectedGame->getResolvedGrid());
    $this->assertEquals($game->getCurrentGrid(), $expectedGame->getCurrentGrid());
    $this->assertEquals($game->getTurn(), $expectedGame->getTurn());
    $this->assertEquals($game->getIsVictory(), $expectedGame->getIsVictory());
  }

  public function testMove() {    
    $mockedResponse = [
      'Grid' => array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      ),
      'IsVictory' => false
    ];

    $gameApi = $this->createGameApi($mockedResponse);

    $game = $gameApi->move(
      new Game(
        'mockedToken',
        array(
          array(1, 2, 3),
          array(4, 5, 6),
          array(7, 8, 0)
        ),
        array(
          array(1, 2, 3),
          array(4, 0, 5),
          array(7, 8, 6)
        )
      ),
      5
    );

    $expectedGame = new Game(
      'mockedToken',
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      )
    );
    $expectedGame->addTurn();
    $this->assertEquals($game->getToken(), $expectedGame->getToken());    
    $this->assertEquals($game->getResolvedGrid(), $expectedGame->getResolvedGrid());
    $this->assertEquals($game->getCurrentGrid(), $expectedGame->getCurrentGrid());
    $this->assertEquals($game->getTurn(), $expectedGame->getTurn());
    $this->assertEquals($game->getIsVictory(), $expectedGame->getIsVictory());
  }
  
  public function testSuggest() {    
    $mockedResponse = [
      'Tile' => 3
    ];

    $gameApi = $this->createGameApi($mockedResponse);

    $suggestion = $gameApi->suggest(
      new Game(
        'mockedToken',      
        array(
          array(1, 2, 3),
          array(4, 5, 6),
          array(7, 8, 0)
        ),
        array(
          array(1, 2, 3),
          array(4, 0, 5),
          array(7, 8, 6)
        )
      )
    );  

    $expectedSuggestion = 3;

    $this->assertEquals($suggestion, $expectedSuggestion);
  }
}
