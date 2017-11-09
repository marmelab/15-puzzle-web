<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class Game {
  /**
   * @ORM\Column(type="guid")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="UUID")
   */
  private $id;

  /**
   * @ORM\Column(type="json_array")
   */
  private $resolvedGrid;

  /**
   * @ORM\Column(type="json_array")
   */
  private $currentGrid;
  
  /**
   * @ORM\Column(type="integer")
   */
  private $turn;
  
  /**
   * @ORM\Column(type="boolean")
   */
  private $isVictory;
  
  public function __construct($resolvedGrid, $currentGrid, $isVictory=false) {
    $this->resolvedGrid =$resolvedGrid;
    $this->currentGrid = $currentGrid;
    $this->turn = 0;
    $this->isVictory = $isVictory;
  }

  // Getters

  public function getId() : string {
    return $this->id;
  }

  public function getResolvedGrid() : Array {
    return $this->resolvedGrid;
  }

  public function getCurrentGrid() : Array {
    return $this->currentGrid;
  }

  public function getTurn() : int {
    return $this->turn;
  }

  public function getIsVictory() : bool {
    return $this->isVictory;
  }

  // Setters

  public function setId(string $id) {
    $this->id = $id;
  }
  
  public function setResolvedGrid(Array $grid) {
    $this->resolvedGrid = $grid;
  }

  public function setCurrentGrid(Array $grid) {
    $this->currentGrid = $grid;
  }

  private function setTurn(int $turn) {
    $this->turn = $turn;
  }

  public function setIsVictory(bool $isVictory) {
    $this->isVictory = $isVictory;
  }

  // Methods

  public function addTurn() {
    $this->turn++;
  }
}
