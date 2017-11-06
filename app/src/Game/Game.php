<?php

namespace App\Game;

class Game {
  private $initialGrid;
  private $grid;

  // Getters

  public function getGrid() {
    return $this->grid;
  }

  public function getInitialGrid() {
    return $this->initialGrid;
  }

  // Setters

  public function setGrid($grid) {
    $this->grid = $grid;
  }
  
  public function setInitialGrid($grid) {
    $this->initialGrid = $grid;
  }
}
