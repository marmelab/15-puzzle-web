<?php

namespace App\Api;

use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use App\Entity\Game;
use App\Api\GameResponse;
use App\Api\MoveResponse;
use App\Api\SuggestResponse;

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
    try {
      $response = $this->client->post('/move-tile', [
        'body' => json_encode([
          'InitialGrid' => $game->getResolvedGrid(),
          'Grid' => $game->getCurrentGrid(),
          'TileNumber' => $tile
        ])
      ]);
    } catch (ConnectException $e) {
      return $game;
    }

    $moveResponse = $this->serializer->deserialize($response->getBody(), MoveResponse::class, 'json');
    $game->setCurrentGrid($moveResponse->getGrid());
    $game->addTurn();
    $game->setIsVictory($moveResponse->getIsVictory());
    return $game;
  }

  public function suggest(Game $game) : int {
    $response = $this->client->get('/suggest', [
      'query' => [
        'Grid' => $json_encode($game->getCurrentGrid()),
        'InitialGrid' => json_encode($game->getResolvedGrid())
    ]]);
    $suggestResponse = $this->serializer->deserialize($response->getBody(), SuggestResponse::class, 'json');
    return $suggestResponse->getTile();
  }
}
