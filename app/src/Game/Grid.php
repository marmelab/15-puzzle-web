<?php

namespace App\Game;

class Grid {
  private $grid;

  // Getters

  public function getGrid() : Array {
    return $this->grid;
  }

  // Setters

  public function setGrid(Array $grid) {
    $this->grid = $grid;
  }  
}
