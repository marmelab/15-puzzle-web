<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use App\Entity\Player;
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
  protected $id;

  /**
   * @ORM\Column(type="json_array")
   */
  protected $resolvedGrid;

  /**
   * @ORM\OneToOne(targetEntity="Player", mappedBy="id")
   * @ORM\JoinColumn(name="player1", referencedColumnName="id")
   */
  protected $player1;

  /**
   * @ORM\OneToOne(targetEntity="Player", mappedBy="id")
   * @ORM\JoinColumn(name="player2", referencedColumnName="id")
   */
  protected $player2;

  /**
   * @ORM\OneToOne(targetEntity="Player", mappedBy="id")
   * @ORM\JoinColumn(name="winner", referencedColumnName="id")
   */
  protected $winner;

  /**
   * @ORM\Column(type="boolean")
   */
  protected $isMultiplayer;

  public function __construct($resolvedGrid, $isMultiplayer = false) {
    $this->resolvedGrid =$resolvedGrid;
    $this->isMultiplayer = $isMultiplayer;
  }

  // Getters

  public function getId() : int {
    return $this->id;
  }

  public function getResolvedGrid() : Array {
    return $this->resolvedGrid;
  }

  public function getPlayer1() : Player {
    return $this->player1;
  }

  public function getPlayer2() : ?Player {
    return $this->player2;
  }

  public function getWinner() : ?Player {
    return $this->winner;
  }

  public function getIsMultiplayer() : bool {
    return $this->isMultiplayer;
  }

  // Setters

  public function setId(int $id) {
    $this->id = $id;
  }

  public function setResolvedGrid(Array $grid) {
    $this->resolvedGrid = $grid;
  }

  public function setPlayer1(Player $player) {
    $this->player1 = $player;
  }

  public function setPlayer2(Player $player) {
    $this->player2 = $player;
  }

  public function setWinner(Player $player) {
    $this->winner = $player;
  }

  public function setIsMultiplayer(bool $isMultiplayer) {
    $this->isMultiplayer = $isMultiplayer;
  }

  // Methods

  public function isFull() {
    return (!$this->getIsMultiplayer() && $this->player1 != null) || ($this->getIsMultiplayer() && $this->player1 != null && $this->player2 != null);
  }
}
