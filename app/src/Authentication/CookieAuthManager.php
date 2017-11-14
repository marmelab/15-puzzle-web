<?php

namespace App\Authentication;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;
use App\Entity\Player;

class CookieAuthManager {
  public const COOKIE_NAME = 'current-puzzle';

  public static function isPlayer(Request $request, Game $game) : bool {
    $token = $request->cookies->get(self::COOKIE_NAME);
    $player1 = $game->getPlayer1();
    $player2 = $game->getPlayer2();

    if ($player1 && $player1->getToken() === $token || $player2 && $player2->getToken() === $token) {
      return true;
    }
    return false;
  }

  public static function getPlayer(Request $request, Game $game) {
    $token = $request->cookies->get(self::COOKIE_NAME);
    if ($game->getIsMultiplayer() && $game->getPlayer2() && $game->getPlayer2()->getToken() == $token) {
      return $game->getPlayer2();
    }
    return $game->getPlayer1();
  }

  public static function setPlayer(Response $response, Player $player) {
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME, $player->getToken()));
  }

  public static function removePlayer(Response $response) {
    $response->headers->clearCookie(self::COOKIE_NAME);
  }
}
