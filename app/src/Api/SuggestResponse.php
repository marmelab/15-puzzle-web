<?php

namespace App\Api;

class SuggestResponse {
  private $tile;

  // Getters

  public function getTile() : int {
    return $this->tile;
  }

  // Setters

  public function setTile(int $tile) {
    $this->tile = $tile;
  }
}
