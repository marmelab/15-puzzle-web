<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class Game {
  private const TOKEN_LENGTH = 10;

  /**
   * @ORM\Column(type="guid")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="UUID")
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

  public function __construct($resolvedGrid, $currentGrid, $isVictory=false) {
    $this->token = bin2hex(random_bytes(self::TOKEN_LENGTH));
    $this->resolvedGrid =$resolvedGrid;
    $this->currentGrid = $currentGrid;
    $this->turn = 0;
    $this->isVictory = $isVictory;
  }

  // Getters

  public function getId() : string {
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

  public function setId(string $id) {
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
