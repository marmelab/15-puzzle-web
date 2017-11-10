<?php

namespace App\Authentication;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;

class CookieAuthManager {
  public const COOKIE_NAME = 'current-puzzle';
  
  public static function hasAccess($request, $game) {
    return $request->cookies->get(self::COOKIE_NAME) == $game->getToken();
  }

  public static function setAccess($response, $game) {
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME, $game->getToken()));
  }

  public static function removeAccess($response) {
    $response->headers->clearCookie(self::COOKIE_NAME);
  }
}
