<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Api\GameApi;
use App\Entity\Game;
use App\Repository\GameRepository;

class GameController extends Controller {
  public const DEFAULT_SIZE = 4;
  public const COOKIE_NAME = 'current-puzzle';

  private $api;
  private $twig;

  public function __construct(\Twig_Environment $twig, GameApi $gameApi, GameRepository $gameRepository) {
    $this->twig = $twig;
    $this->api = $gameApi;
    $this->gameRepository = $gameRepository;
  }

  private function hasAccess($request, $game) {
    return $request->cookies->get(self::COOKIE_NAME) == $game->getToken();
  }

  private function setAccess($response, $game) {
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME, $game->getToken()));
  }

  private function removeAccess($response) {
    $response->headers->clearCookie(self::COOKIE_NAME);  
  }

  private function renderGrid(string $id, Game $game, $hasAccess) {
    return new Response($this->twig->render('game.html.twig', [
      'id' => $id,
      'grid' => $game->getCurrentGrid(),
      'turn' => $game->getTurn(),
      'isVictory' => $game->getIsVictory(),
      'hasAccess' => $hasAccess
    ]));
  }

  public function play(Request $request, string $id) {
    $game = $this->gameRepository->findGameById($id);
    return $this->renderGrid($id, $game, $this->hasAccess($request, $game));
  }

  public function new() {
    $game = $this->api->new(self::DEFAULT_SIZE);
    $this->gameRepository->save($game);

    $response = $this->redirectToRoute('game', array('id' => $game->getId()));
    $this->setAccess($response, $game);    
    return $response;    
  }

  public function cancel($id) {
    $this->gameRepository->remove($id);

    $response = $this->redirectToRoute('index');
    $this->removeAccess($response);
    return $response;
  }

  public function move(Request $request, string $id, int $tile) {
    $game = $this->gameRepository->findGameById($id);

    if ($this->hasAccess($request, $game) && !$game->getIsVictory()) {
      $newGame = $this->api->move($game, $tile);
      $this->gameRepository->save($newGame);
    }

    return $this->redirectToRoute('game', array('id' => $id));    
  }
}
