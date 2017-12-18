<?php

namespace App\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;
use App\Entity\Player;

class TokenAuthManager {
  public static function isPlayer(Request $request, Game $game, string $token = null) : bool {
    $player1 = $game->getPlayer1();
    $player2 = $game->getPlayer2();

    return $player1->getToken() === $token ||
      $game->getIsMultiplayer() &&
      $player2 && $player2->getToken() === $token;
  }

  public static function getPlayer(Request $request, Game $game, string $token) {
    if ($game->getIsMultiplayer() && $game->getPlayer2() && $game->getPlayer2()->getToken() === $token) {
      return $game->getPlayer2();
    }
    return $game->getPlayer1();
  }
}
