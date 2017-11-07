<?php

namespace App\Game;

class Game {
  private $initialGrid;
  private $grid;

  // Getters

  public function getGrid() : Array {
    return $this->grid;
  }

  public function getInitialGrid() : Array {
    return $this->initialGrid;
  }

  // Setters

  public function setGrid(Array $grid) {
    $this->grid = $grid;
  }
  
  public function setInitialGrid(Array $grid) {
    $this->initialGrid = $grid;
  }
}
