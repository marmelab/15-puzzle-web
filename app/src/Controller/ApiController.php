<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
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

  public function __construct(GameApi $gameApi, GameRepository $gameRepository, EntityManager $em) {
    $this->api = $gameApi;
    $this->em = $em;
    $this->gameRepository = $gameRepository;
  }

  public function new(Request $request) {
    $body = json_decode($request->getContent(), true);
    $apiResponse = $this->api->new(self::DEFAULT_SIZE, $body['mode'] === 'multi');
    $this->em->persist($apiResponse['player']);
    $this->em->persist($apiResponse['game']);
    $this->em->flush();

    return new JsonResponse([
      'id' => $apiResponse['game']->getId(),
      'token' => $apiResponse['player']->getToken()
    ]);
  }

  public function game(GameContext $context) {
    $game = $context->getGame();
    $currentPlayer = $context->getPlayer();
    $player = $currentPlayer ?: $game->getPlayer1();

    if ($game->getIsMultiplayer()) {
      if ($currentPlayer) {
        $otherPlayer = $player->getId() === $game->getPlayer1()->getId() ? $game->getPlayer2() : $game->getPlayer1();
      } else {
        $otherPlayer = $game->getPlayer2();
      }

      return new JsonResponse([
        'id' => $game->getId(),
        'isMultiplayer' => $game->getIsMultiplayer(),
        'currentPlayer' => $player,
        'otherPlayer' => $otherPlayer,
        'winner' => $game->getWinner()
      ]);
    }
    return new JsonResponse([
      'id' => $game->getId(),
      'isMultiplayer' => $game->getIsMultiplayer(),
      'currentPlayer' => $player,
      'winner' => $game->getWinner()
    ]);
  }

  public function cancel(GameContext $context) {
    if ($context->getIsPlayer()) {
      $this->em->remove($context->getGame());
      $this->em->flush();

      return new Response(
        Response::HTTP_OK
      );
    }
    return new Response(
      Response::HTTP_INTERNAL_SERVER_ERROR
    );
  }

  public function join(GameContext $context, TokenGenerator $tokenGenerator) {
    $game = $context->getGame();

    if ($game->isFull()) {
      return new Response(
        Response::HTTP_INTERNAL_SERVER_ERROR
      );
    }

    $currentGrid = $game->getPlayer1()->getCurrentGrid();
    $newPlayer = new Player($tokenGenerator->generate(), $currentGrid);
    $this->playerRepository->save($newPlayer);
    $this->em->persist($newPlayer);

    $game->setPlayer2($newPlayer);
    $this->em->persist($game);

    $this->em->flush();

    return new JsonResponse([
      'id' => $game->getId(),
      'token' => $newPlayer->getToken()
    ]);
  }

  public function move(GameContext $context, int $tile) {
    $game = $context->getGame();
    $currentPlayer = $context->getPlayer();

    if ($context->getIsPlayer() && $game->getWinner() === null) {
      $apiResponse = $this->api->move($game, $currentPlayer, $tile);
      $this->em->persist($apiResponse['game']);
      $this->em->persist($currentPlayer);
      $this->em->flush();
    }

    return new JsonResponse([
      'id' => $game->getId(),
      'currentPlayer' => $currentPlayer,
      'winner' => $game->getWinner()
    ]);
  }

  public function games() {
    $gameIds = $this->gameRepository->findOpenMultiplayerGames();

    return new JsonResponse([
      'gameIds' => $gameIds
    ]);
  }
}
