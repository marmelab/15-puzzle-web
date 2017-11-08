<?php

namespace App\Game;

class Game {
  private $initialGrid;
  private $grid;
  private $isVictory;

  public function __construct($initialGrid, $grid, $isVictory=false) {
    $this->setInitialGrid($initialGrid);
    $this->setGrid($grid);
    $this->setIsVictory($isVictory);
  }

  // Getters

  public function getGrid() : Array {
    return $this->grid;
  }

  public function getInitialGrid() : Array {
    return $this->initialGrid;
  }

  public function getIsVictory() : bool {
    return $this->isVictory;
  }

  // Setters

  public function setGrid(Array $grid) {
    $this->grid = $grid;
  }

  public function setInitialGrid(Array $grid) {
    $this->initialGrid = $grid;
  }

  public function setIsVictory(bool $isVictory) {
    $this->isVictory = $isVictory;
  }
}
