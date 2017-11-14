<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 * @ORM\Table
 */
class Player {
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
  private $currentGrid;

  /**
   * @ORM\Column(type="integer")
   */
  private $turn;

  public function __construct($token, $currentGrid) {
    $this->token = $token;
    $this->currentGrid = $currentGrid;
    $this->turn = 0;
  }

  // Getters

  public function getId() : int {
    return $this->id;
  }

  public function getToken() : string {
    return $this->token;
  }

  public function getCurrentGrid() : Array {
    return $this->currentGrid;
  }

  public function getTurn() : int {
    return $this->turn;
  }

  // Setters

  public function setId(int $id) {
    $this->id = $id;
  }

  private function setToken(string $token) {
    $this->token = $token;
  }

  public function setCurrentGrid(Array $grid) {
    $this->currentGrid = $grid;
  }

  private function setTurn(int $turn) {
    $this->turn = $turn;
  }

  // Methods

  public function addTurn() {
    $this->turn++;
  }
}
