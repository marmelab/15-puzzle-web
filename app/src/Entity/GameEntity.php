<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class GameEntity {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue
   */
  private $id;

  /**
   * @ORM\Column(type="string")
   */
  private $game;

  public function __construct($game) {
    $this->game = $game;
  }

  // Getters

  public function getId() : int {
    return $this->id;
  }

  public function getGame() : string {
    return $this->game;
  }

  // Setters

  public function setId(int $id) {
    $this->id = $id;
  }
  
  public function setGame(string $game) {
    $this->game = $game;
  }
}
