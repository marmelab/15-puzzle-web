<?php

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use App\ArgumentResolver\GameContext;
use App\Authentication\CookieAuthManager;
use App\Authentication\TokenAuthManager;
use App\Repository\GameRepository;


class GameContextResolver implements ArgumentValueResolverInterface {
  public const AUTHORIZATION_TYPE = 'Bearer';

  private $gameRepository;

  public function __construct(GameRepository $gameRepository) {
    $this->gameRepository = $gameRepository;
  }

  public function supports(Request $request, ArgumentMetadata $argument) {
    return GameContext::class === $argument->getType();
  }

  public function resolve(Request $request, ArgumentMetadata $argument) {
    $game = $this->gameRepository->findGameById($request->get('id'));

    $authTokenArray = explode(' ', $request->headers->get('Authorization'));
    $token = count($authTokenArray) === 2 && $authTokenArray[0] === self::AUTHORIZATION_TYPE ? $authTokenArray[1] : '';
    $isPlayer = $token !== '' ? TokenAuthManager::isPlayer($request, $game, $token) : CookieAuthManager::isPlayer($request, $game);

    $gameContext = new GameContext();
    $gameContext->setGame($game);
    $gameContext->setIsPlayer($isPlayer);
    if ($isPlayer) {
      $player = $token !== '' ? TokenAuthManager::getPlayer($request, $game, $token) : CookieAuthManager::getPlayer($request, $game);
      $gameContext->setPlayer($player);
    }
    yield $gameContext;
  }
}
