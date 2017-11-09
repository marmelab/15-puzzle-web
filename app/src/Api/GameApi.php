<?php

namespace App\Api;

use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client;
use App\Entity\GameEntity;
use App\Api\GameResponse;
use App\Api\MoveResponse;

class GameApi {
  private $client;
  private $serializer;

  public function __construct(Client $gameApiClient, Serializer $serializer) {
    $this->client = $gameApiClient;
    $this->serializer = $serializer;
  }

  public function new(int $size) : GameEntity {
    $response = $this->client->get('/new', [
      'query' => 'size=' . $size
    ]);
    $gameResponse = $this->serializer->deserialize($response->getBody(), GameResponse::class, 'json');
    return new GameEntity($gameResponse->getInitialGrid(), $gameResponse->getGrid());
  }

  public function move(GameEntity $game, int $tile) : GameEntity {
    $response = $this->client->post('/move-tile', [
      'body' => json_encode([
        'InitialGrid' => $game->getResolvedGrid(),
        'Grid' => $game->getCurrentGrid(),
        'TileNumber' => $tile
      ])
    ]);
    $moveResponse = $this->serializer->deserialize($response->getBody(), MoveResponse::class, 'json');
    $game->setGrid($moveResponse->getCurrentGrid());
    $game->addTurn();
    $game->setIsVictory($moveResponse->getIsVictory());
    return $game;
  }

  public function suggest(GameEntity $game) : int {
    $response = $this->client->get('/suggest', [
      'query' => [
        'Grid' => $json_encode($game->getCurrentGrid()),
        'InitialGrid' => json_encode($game->getResolvedGrid())
    ]]);
    $suggestResponse = $this->serializer->deserialize($response->getBody(), SuggestResponse::class, 'json');
    return $suggestResponse->getTile();
  }
}
