<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GameRepository;

class MainController extends Controller {
  private $twig;
  private $gameRepository;

  public function __construct(\Twig_Environment $twig, GameRepository $gameRepository) {
    $this->twig = $twig;
    $this->gameRepository = $gameRepository;
  }

  public function start() {
    $listGames = $this->gameRepository->findOpenMultiplayerGames();
    return new Response($this->twig->render('main.html.twig', [
      'listGames' => $listGames
    ]));
  }
}
