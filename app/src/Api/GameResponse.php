<?php

namespace App\Api;

class GameResponse {
  private $initialGrid;
  private $grid;

  // Getters
  
  public function getInitialGrid() : Array {
    return $this->initialGrid;
  }

  public function getGrid() : Array {
    return $this->grid;
  }

  // Setters
  
  public function setInitialGrid(Array $initialGrid) {
    $this->initialGrid = $initialGrid;
  }

  public function setGrid(Array $grid) {
    $this->grid = $grid;
  }
}
