<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Api\GameApi;
use App\Game\Game;

class GameController extends Controller {
  private const DEFAULT_SIZE = 4;
  private $api;
  private $twig;

  public function __construct(\Twig_Environment $twig, GameApi $gameApi) {
    $this->api = $gameApi;
    $this->twig = $twig;
  }
 
  public function start() {
    $game = $this->api->new(4);
    return new Response($this->twig->render('game.html.twig', [
      'grid' => $game->getGrid()
    ]));
  }
}
