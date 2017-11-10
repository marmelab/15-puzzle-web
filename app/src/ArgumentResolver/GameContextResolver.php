<?php

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use App\ArgumentResolver\GameContext;
use App\Authentication\CookieAuthManager;
use App\Repository\GameRepository;


class GameContextResolver implements ArgumentValueResolverInterface {
  private $gameRepository;

  public function __construct(GameRepository $gameRepository){
    $this->gameRepository = $gameRepository;    
  }

  public function supports(Request $request, ArgumentMetadata $argument) {
    return GameContext::class === $argument->getType();
  }

  public function resolve(Request $request, ArgumentMetadata $argument) {
    $game = $this->gameRepository->findGameById($request->get('id'));
    $hasAccess = CookieAuthManager::hasAccess($request, $game);

    $gameContext = new GameContext();
    $gameContext->setGame($game);
    $gameContext->setHasAccess($hasAccess);
    yield $gameContext;
  }
}
