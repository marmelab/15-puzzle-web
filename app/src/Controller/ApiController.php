<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Api\GameApi;
use App\Api\TokenGenerator;
use App\Entity\Game;
use App\Entity\Player;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\ArgumentResolver\GameContext;

class ApiController extends Controller {
  public const DEFAULT_SIZE = 4;

  private $serializer;
  private $api;
  private $gameRepository;
  private $playerRepository;

  public function __construct(GameApi $gameApi, GameRepository $gameRepository, PlayerRepository $playerRepository) {
    $this->api = $gameApi;
    $this->gameRepository = $gameRepository;
    $this->playerRepository = $playerRepository;
  }

  public function new(Request $request) {
    $mode = $request->get('mode');
    $apiResponse = $this->api->new(self::DEFAULT_SIZE, $mode && $mode == 'multi');
    $this->playerRepository->save($apiResponse['player']);
    $this->gameRepository->save($apiResponse['game']);

    $response = new JsonResponse();
    $response->setData([
      'id' => $apiResponse['game']->getId(),
      'token' => $apiResponse['player']->getToken()
    ]);
    return $response;
  }

  public function game(GameContext $context) {
    $game = $context->getGame();
    $currentPlayer = $context->getPlayer();
    $player = $currentPlayer ?: $game->getPlayer1();

    $response = new JsonResponse();

    if ($game->getIsMultiplayer()) {
      if ($currentPlayer) {
        $otherPlayer = $player->getId() == $game->getPlayer1()->getId() ? $game->getPlayer2() : $game->getPlayer1();
      } else {
        $otherPlayer = $game->getPlayer2();
      }

      $response->setData([
        'id' => $game->getId(),
        'currentPlayer' => $player,
        'otherPlayer' => $otherPlayer,
        'winner' => $game->getWinner()
      ]);
    } else {
      $response->setData([
        'id' => $game->getId(),
        'currentPlayer' => $player,
        'winner' => $game->getWinner()
      ]);
    }
    return $response;
  }

  public function cancel(GameContext $context) {
    if ($context->getIsPlayer()) {
      $this->gameRepository->remove($context->getGame()->getId());
      return new Response(
        'Content',
        Response::HTTP_OK,
        ['content-type' => 'text/html']
      );
    }
    return new Response(
      'Content',
      Response::HTTP_INTERNAL_SERVER_ERROR,
      ['content-type' => 'text/html']
    );
  }

  public function join(GameContext $context, TokenGenerator $tokenGenerator) {
    $game = $context->getGame();

    if ($game->isFull()) {
      return new Response(
        'Content',
        Response::HTTP_INTERNAL_SERVER_ERROR,
        ['content-type' => 'text/html']
      );
    }

    $currentGrid = $game->getPlayer1()->getCurrentGrid();
    $newPlayer = new Player($tokenGenerator->generate(), $currentGrid);
    $this->playerRepository->save($newPlayer);

    $game->setPlayer2($newPlayer);
    $this->gameRepository->save($game);

    $response = new JsonResponse();
    $response->setData([
      'id' => $game->getId(),
      'token' => $newPlayer->getToken()
    ]);
    return $response;
  }

  public function move(GameContext $context, int $tile) {
    $game = $context->getGame();
    $player = $context->getPlayer();

    if ($context->getIsPlayer() && $game->getWinner() == null) {
      $apiResponse = $this->api->move($game, $player, $tile);
      $this->gameRepository->save($apiResponse['game']);
    }

    $response = new JsonResponse();
    $response->setData([
      'id' => $game->getId(),
      'currentPlayer' => $this->serializer->serialize($player, 'json'),
      'otherPlayer' => $this->serializer->serialize($otherPlayer, 'json'),
      'winner' => $this->serializer->serialize($game->getWinner(), 'json')
    ]);
    return $response;
  }

  public function games() {
    $gameIds = $this->gameRepository->findOpenMultiplayerGames();

    $response = new JsonResponse();
    $response->setData([
      'game_ids' => $gameIds
    ]);
    return $response;
  }
}
