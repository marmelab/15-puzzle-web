<?php

namespace App\ArgumentResolver;

use App\Entity\Game;

class GameContext {

  private $game;
  private $isOwner;

  public function __construct() {}

  // Setters

  public function setGame(Game $game) {
    $this->game = $game;
  }

  public function setIsOwner(bool $isOwner) {    
    $this->isOwner = $isOwner;
  }

  // Getters

  public function getGame() : Game {
    return $this->game;
  }

  public function getIsOwner() : bool {
    return $this->isOwner;
  }
}
