<?php

namespace App\Api;

class MoveResponse {
  private $grid;
  private $isVictory;

  // Getters

  public function getGrid() : Array {
    return $this->grid;
  }

  public function getIsVictory() : bool {
    return $this->isVictory;
  }

  // Setters

  public function setGrid(Array $grid) {
    $this->grid = $grid;
  }

  public function setIsVictory(bool $isVictory) {
    $this->isVictory = $isVictory;
  }
}
