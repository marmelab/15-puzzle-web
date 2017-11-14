<?php

namespace App\Api;

use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use App\Entity\Game;
use App\Entity\Player;
use App\Api\TokenGenerator;
use App\Api\GameResponse;
use App\Api\MoveResponse;
use App\Api\SuggestResponse;

class GameApi {
  private $client;
  private $serializer;
  private $tokenGenerator;

  public function __construct(Client $gameApiClient, Serializer $serializer, TokenGenerator $tokenGenerator) {
    $this->client = $gameApiClient;
    $this->serializer = $serializer;
    $this->tokenGenerator = $tokenGenerator;
  }

  public function new(int $size, bool $isMultiplayer) : Array {
    $response = $this->client->get('/new', [
      'query' => 'size=' . $size
    ]);
    $gameResponse = $this->serializer->deserialize($response->getBody(), GameResponse::class, 'json');

    $player = new Player($this->tokenGenerator->generate(), $gameResponse->getGrid());
    $game = new Game($gameResponse->getInitialGrid(), $isMultiplayer);
    $game->setPlayer1($player);
    return ['game' => $game, 'player' => $player];
  }

  public function move(Game $game, Player $player, int $tile) : Array {
    try {
      $response = $this->client->post('/move-tile', [
        'body' => json_encode([
          'InitialGrid' => $game->getResolvedGrid(),
          'Grid' => $player->getCurrentGrid(),
          'TileNumber' => $tile
        ])
      ]);
    } catch (ConnectException $e) {
      return ['game' => $game, 'player' => $player];
    }

    $moveResponse = $this->serializer->deserialize($response->getBody(), MoveResponse::class, 'json');
    $player->setCurrentGrid($moveResponse->getGrid());
    $player->addTurn();
    if ($moveResponse->getIsVictory()) {
      $game->setWinner($player);
    }
    return ['game' => $game, 'player' => $player];
  }

  public function suggest(Game $game, Player $player) : int {
    $response = $this->client->get('/suggest', [
      'query' => [
        'Grid' => json_encode($player->getCurrentGrid()),
        'InitialGrid' => json_encode($game->getResolvedGrid())
    ]]);
    $suggestResponse = $this->serializer->deserialize($response->getBody(), SuggestResponse::class, 'json');
    return $suggestResponse->getTile();
  }
}
