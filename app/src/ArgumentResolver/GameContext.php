<?php

namespace App\ArgumentResolver;

use App\Entity\Game;
use App\Entity\Player;

class GameContext {

  private $game;
  private $player;
  private $isPlayer;

  public function __construct() {}

  // Setters

  public function setGame(Game $game) {
    $this->game = $game;
  }

  public function setPlayer(Player $player) {
    $this->player = $player;
  }

  public function setIsPlayer(bool $isPlayer) {    
    $this->isPlayer = $isPlayer;
  }

  // Getters

  public function getGame() : ?Game {
    return $this->game;
  }

  public function getPlayer() : ?Player {
    return $this->player;
  }

  public function getIsPlayer() : bool {
    return $this->isPlayer;
  }
}
