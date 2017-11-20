<?php

namespace Tests\App\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Entity\Player;

class GameTest extends TestCase {
  public function testIsFullWithoutPlayers() {
    $game = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      )
    );

    $this->assertEquals(false, $game->isFull());
  }

  public function testIsFullSingleplayer() {
    $game = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      false
    );
    $player1 = new Player(
      'randomtoken',
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );
    $game->setPlayer1($player1);
    $this->assertEquals(true, $game->isFull());
  }

  public function testIsFullMultiplayer() {
    $game = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      true
    );
    $player1 = new Player(
      'randomtoken',
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );
    $player2 = new Player(
      'randomtoken2',
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );
    $game->setPlayer1($player1);
    $game->setPlayer2($player2);
    $this->assertEquals(true, $game->isFull());
  }

  public function testJsonSerialize() {
    $game = new Game(
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      true
    );
    $player1 = new Player(
      'randomtoken',
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );
    $player2 = new Player(
      'randomtoken2',
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );
    $game->setId(12);
    $game->setPlayer1($player1);
    $game->setPlayer2($player2);

    $expectedGameJson = [
      'game' => [
        'id' => 12,
        'resolvedGrid' => array(
          array(1, 2, 3),
          array(4, 5, 6),
          array(7, 8, 0)
        ),
        'player1' => $player1,
        'player2' => $player2,
        'winner' => null,
        'isMultiplayer' => true,
        'isFull' => true
      ]
    ];

    $this->assertEquals($expectedGameJson, $game->jsonSerialize());
  }
}
