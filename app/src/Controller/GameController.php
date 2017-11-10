<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Api\GameApi;
use App\Authentication\CookieAuthManager;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\ArgumentResolver\GameContext;

class GameController extends Controller {
  public const DEFAULT_SIZE = 4;

  private $api;
  private $twig;

  public function __construct(\Twig_Environment $twig, GameApi $gameApi, GameRepository $gameRepository) {
    $this->twig = $twig;
    $this->api = $gameApi;
    $this->gameRepository = $gameRepository;
  }

  public function play(GameContext $context) {
    return new Response($this->twig->render('game.html.twig', [
      'game' => $context->getGame(),
      'isOwner' => $context->getIsOwner()
    ]));
  }

  public function new() {
    $game = $this->api->new(self::DEFAULT_SIZE);
    $this->gameRepository->save($game);

    $response = $this->redirectToRoute('game', [
      'id' => $game->getId()
    ]);
    CookieAuthManager::setOwner($response, $game);

    return $response;    
  }

  public function cancel(GameContext $context) {
    $response = $this->redirectToRoute('index');

    if ($context->getIsOwner()) {
      $this->gameRepository->remove($context->getGame()->getId());
      CookieAuthManager::removeOwner($response);
    }

    return $response;
  }

  public function move(GameContext $context, int $tile) {
    $game = $context->getGame();

    if ($context->getIsOwner() && !$game->getIsVictory()) {
      $newGame = $this->api->move($game, $tile);
      $this->gameRepository->save($newGame);
    }

    return $this->redirectToRoute('game', array('id' => $game->getId()));    
  }
}
