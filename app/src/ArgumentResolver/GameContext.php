<?php

namespace App\ArgumentResolver;

use App\Entity\Game;

class GameContext {

  private $game;
  private $hasAccess;

  public function __construct() {}

  // Setters

  public function setGame(Game $game) {
    $this->game = $game;
  }

  public function setHasAccess(bool $hasAccess) {    
    $this->hasAccess = $hasAccess;
  }

  // Getters

  public function getGame() : Game {
    return $this->game;
  }

  public function getHasAccess() : bool {
    return $this->hasAccess;
  }
}
