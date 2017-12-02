<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Api\GameApi;
use App\Api\TokenGenerator;
use App\Authentication\CookieAuthManager;
use App\Entity\Game;
use App\Entity\Player;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\ArgumentResolver\GameContext;

class GameController extends Controller {
  public const DEFAULT_SIZE = 4;

  private $api;
  private $twig;
  private $gameRepository;
  private $playerRepository;

  public function __construct(\Twig_Environment $twig, GameApi $gameApi, GameRepository $gameRepository, PlayerRepository $playerRepository) {
    $this->twig = $twig;
    $this->api = $gameApi;
    $this->gameRepository = $gameRepository;
    $this->playerRepository = $playerRepository;
  }

  public function play(GameContext $context) {
    $game = $context->getGame();
    $currentPlayer = $context->getPlayer();
    $player = $currentPlayer ?: $game->getPlayer1();
    if ($game->getIsMultiplayer()) {
      if ($currentPlayer) {
        $otherPlayer = $player->getId() === $game->getPlayer1()->getId() ? $game->getPlayer2() : $game->getPlayer1();
      } else {
        $otherPlayer = $game->getPlayer2();
      }

      return new Response($this->twig->render('game_multi.html.twig', [
        'game' => $game,
        'player' => $player,
        'otherPlayer' => $otherPlayer,
        'winner' => $game->getWinner(),
        'isOwner' => $context->getIsPlayer()
      ]));
    }

    return new Response($this->twig->render('game_single.html.twig', [
      'game' => $game,
      'player' => $player,
      'winner' => $game->getWinner(),
      'isOwner' => $context->getIsPlayer()
    ]));
  }

  public function new(string $mode) {
    $apiResponse = $this->api->new(self::DEFAULT_SIZE, $mode === 'multi');
    $this->playerRepository->save($apiResponse['player']);
    $this->gameRepository->save($apiResponse['game']);

    $response = $this->redirectToRoute('game', [
      'id' => $apiResponse['game']->getId()
    ]);
    CookieAuthManager::setPlayer($response, $apiResponse['player']);
    return $response;
  }

  public function cancel(GameContext $context) {
    $response = $this->redirectToRoute('index');

    if ($context->getIsPlayer()) {
      $this->gameRepository->remove($context->getGame()->getId());
      CookieAuthManager::removePlayer($response);
    }

    return $response;
  }

  public function join(GameContext $context, TokenGenerator $tokenGenerator) {
    $game = $context->getGame();

    $response = $this->redirectToRoute('game', [
      'id' => $game->getId()
    ]);

    if ($game->isFull()) {
      return $response;
    }
    $currentGrid = $game->getPlayer1()->getCurrentGrid();
    $newPlayer = new Player($tokenGenerator->generate(), $currentGrid);
    $this->playerRepository->save($newPlayer);

    $game->setPlayer2($newPlayer);
    $this->gameRepository->save($game);

    CookieAuthManager::setPlayer($response, $newPlayer);
    return $response;
  }

  public function move(GameContext $context, int $tile) {
    $game = $context->getGame();
    $player = $context->getPlayer();
    if ($context->getIsPlayer() && $game->getWinner() === null) {
      $apiResponse = $this->api->move($game, $player, $tile);
      $this->gameRepository->save($apiResponse['game']);
    }

    return $this->redirectToRoute('game', [
      'id' => $game->getId()
    ]);
  }
}
