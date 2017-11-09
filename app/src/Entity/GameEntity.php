<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class GameEntity {
  /**
   * @ORM\Column(type="guid", length=6)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="UUID")
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

  public function getId() : string {
    return $this->id;
  }

  public function getGame() : string {
    return $this->game;
  }

  // Setters

  public function setId(string $id) {
    $this->id = $id;
  }
  
  public function setGame(string $game) {
    $this->game = $game;
  }
}
