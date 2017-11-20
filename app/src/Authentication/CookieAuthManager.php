<?php

namespace App\Authentication;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Authentication\TokenAuthManager;
use App\Entity\Game;
use App\Entity\Player;

class CookieAuthManager {
  public const COOKIE_NAME = 'current-puzzle';

  public static function isPlayer(Request $request, Game $game) : bool {
    return TokenAuthManager::isPlayer($request, $game, $request->cookies->get(self::COOKIE_NAME));
  }

  public static function getPlayer(Request $request, Game $game) {
    return TokenAuthManager::getPlayer($request, $game, $request->cookies->get(self::COOKIE_NAME));
  }

  public static function setPlayer(Response $response, Player $player) {
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME, $player->getToken()));
  }

  public static function removePlayer(Response $response) {
    $response->headers->clearCookie(self::COOKIE_NAME);
  }
}
