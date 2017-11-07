<?php

namespace App\Api;

use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client;
use App\Game\Game;

class GameApi {
  private $client;
  private $serializer;

  public function __construct(Client $gameApiClient, Serializer $serializer) {
    $this->client = $gameApiClient;
    $this->serializer = $serializer;
  }

  public function new(int $size) : Game {
    $response = $this->client->get('/new', [
      'query' => 'size=' . $size
    ]);
    return $this->serializer->deserialize($response->getBody(), Game::class, 'json');
  }

  public function move(Game $game, String $move) : Game {
    $gridJson = json_encode($game->getGrid());
    $moveJson = json_encode($move);
    $response = $this->client->post('/move-tile', [
      'json' => [
        'grid' => $gridJson,
        'move' => $moveJson
      ]
    ]);

    $newGame = new Game();
    $newGame->setInitialGrid($game->getInitialGrid());
    $newGame->setGrid(json_decode($response->getBody()));
    return $newGame;
  }

  public function suggest(Game $game) : String {
    $gridJson = json_encode($game->getGrid());
    $initialGridJson = json_encode($game->getInitialGrid());
    $response = $this->client->get('/suggest', [
      'json' => [
        'grid' => $gridJson,
        'initial_grid' => $initialGridJson
    ]]);
    return $response->getBody();
  }
}
