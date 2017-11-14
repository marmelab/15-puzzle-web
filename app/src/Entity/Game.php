<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class Game {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string")
   */
  private $token;

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

  public function __construct($token, $resolvedGrid, $currentGrid, $isVictory=false) {
    $this->token = $token;
    $this->resolvedGrid =$resolvedGrid;
    $this->currentGrid = $currentGrid;
    $this->turn = 0;
    $this->isVictory = $isVictory;
  }

  // Getters

  public function getId() : int {
    return $this->id;
  }

  public function getToken() : string {
    return $this->token;
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

  public function setId(int $id) {
    $this->id = $id;
  }

  private function setToken(string $token) {
    $this->token = $token;
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
