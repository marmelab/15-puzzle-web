<?php

namespace App\Api;

use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client;
use App\Game\Game;
use App\Api\GameResponse;
use App\Api\MoveResponse;

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
    $gameResponse = $this->serializer->deserialize($response->getBody(), GameResponse::class, 'json');
    return new Game($gameResponse->getInitialGrid(), $gameResponse->getGrid());
  }

  public function move(Game $game, int $tile) : Game {
    $response = $this->client->post('/move-tile', [
      'body' => json_encode([
        'InitialGrid' => $game->getInitialGrid(),
        'Grid' => $game->getGrid(),
        'TileNumber' => $tile
      ])
    ]);
    $moveResponse = $this->serializer->deserialize($response->getBody(), MoveResponse::class, 'json');
    $game->setGrid($moveResponse->getGrid());
    $game->setIsVictory($moveResponse->getIsVictory());
    return $game;
  }

  public function suggest(Game $game) : int {
    $response = $this->client->get('/suggest', [
      'query' => [
        'Grid' => $json_encode($game->getGrid()),
        'InitialGrid' => json_encode($game->getInitialGrid())
    ]]);
    $suggestResponse = $this->serializer->deserialize($response->getBody(), SuggestResponse::class, 'json');
    return $suggestResponse->getTile();
  }
}
