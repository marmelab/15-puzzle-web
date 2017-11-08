<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Api\GameApi;
use App\Repository\GameRepository;
use App\Game\Game;

class GameController extends Controller {
  public const DEFAULT_SIZE = 4;
  private $api;
  private $twig;

  public function __construct(\Twig_Environment $twig, GameApi $gameApi, GameRepository $gameRepository) {
    $this->api = $gameApi;
    $this->twig = $twig;
    $this->gameRepository = $gameRepository;
  }

  private function renderGrid(string $id, Game $game) {
    return new Response($this->twig->render('game.html.twig', [
      'id' => $id,
      'grid' => $game->getGrid(),
      'isVictory' => $game->getIsVictory()
    ]));
  }

  public function play(string $id) {
    $game = $this->gameRepository->findGameById($id);
    return $this->renderGrid($id, $game);
  }

  public function new() {
    $game = $this->api->new(self::DEFAULT_SIZE);
    $gameEntity = $this->gameRepository->createGame($game);
    $this->gameRepository->flush();
    return $this->redirectToRoute('game', array('id' => $gameEntity->getId()));
  }

  public function move(string $id, int  $tile) {
    $game = $this->gameRepository->findGameById($id);
    if (!$game->getIsVictory()) {
      $newGame = $this->api->move($game, $tile);
      $this->gameRepository->updateGame($id, $newGame);
      $this->gameRepository->flush();
    }
    return $this->redirectToRoute('game', array('id' => $id));    
  }
}
