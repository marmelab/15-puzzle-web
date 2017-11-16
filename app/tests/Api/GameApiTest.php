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
use App\Entity\Player;
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

    $apiResponse = $gameApi->new(3, false);
    $expectedPlayer = new Player(
      'mockedToken',
      array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      )
    );
    $expectedPlayer->setId(1);

    $expectedGame = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      )
    );
    $expectedGame->setPlayer1($expectedPlayer);

    $this->assertEquals($apiResponse['player']->getCurrentGrid(), $expectedPlayer->getCurrentGrid());
    $this->assertEquals($apiResponse['player']->getTurn(), $expectedPlayer->getTurn());

    $this->assertEquals($apiResponse['game']->getResolvedGrid(), $expectedGame->getResolvedGrid());
    $this->assertEquals($apiResponse['game']->getPlayer1()->getToken(), $expectedGame->getPlayer1()->getToken());
    $this->assertEquals($apiResponse['game']->getIsMultiplayer(), $expectedGame->getIsMultiplayer());
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
    $player = new Player(
      'mockedToken',
      array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      )
    );
    $player->setId(1);
    $game = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      )
    );
    $game->setPlayer1($player);
    $apiResponse = $gameApi->move($game, $player, 6);

    $expectedPlayer = new Player(
      'mockedToken',
      array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      )
    );
    $expectedPlayer->addTurn();

    $this->assertEquals($apiResponse['player']->getCurrentGrid(), $expectedPlayer->getCurrentGrid());
    $this->assertEquals($apiResponse['player']->getTurn(), $expectedPlayer->getTurn());
  }

  public function testMoveVictory() {
    $mockedResponse = [
      'Grid' => array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      'IsVictory' => true
    ];

    $gameApi = $this->createGameApi($mockedResponse);

    $player = new Player(
      'mockedToken',
      array(
        array(1, 2, 3),
        array(4, 5, 0),
        array(7, 8, 6)
      )
    );
    $player->setId(1);
    $game = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      )
    );
    $game->setPlayer1($player);
    $apiResponse = $gameApi->move($game, $player, 6);

    $expectedPlayer = new Player(
      'mockedToken',
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      )
    );
    $expectedPlayer->setId(1);
    $expectedPlayer->addTurn();

    $expectedGame = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      )
    );
    $expectedGame->setWinner($expectedPlayer);

    $this->assertEquals($apiResponse['player']->getCurrentGrid(), $expectedPlayer->getCurrentGrid());
    $this->assertEquals($apiResponse['player']->getTurn(), $expectedPlayer->getTurn());

    $this->assertEquals($apiResponse['game']->getWinner()->getId(), $expectedPlayer->getId());
  }

  public function testSuggest() {
    $mockedResponse = [
      'Tile' => 3
    ];
    $gameApi = $this->createGameApi($mockedResponse);

    $suggestion = $gameApi->suggest(
      new Game(
        array(
          array(1, 2, 3),
          array(4, 5, 6),
          array(7, 8, 0)
        )
      ),
      new Player(
        'mockedToken',
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
