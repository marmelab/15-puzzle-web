<?php

namespace App\Authentication;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;

class CookieAuthManager {
  public const COOKIE_NAME = 'current-puzzle';
  
  public static function isOwner($request, $game) {
    return $request->cookies->get(self::COOKIE_NAME) == $game->getToken();
  }

  public static function setOwner($response, $game) {
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME, $game->getToken()));
  }

  public static function removeOwner($response) {
    $response->headers->clearCookie(self::COOKIE_NAME);
  }
}
