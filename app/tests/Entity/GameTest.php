<?php

namespace Tests\App\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;

class GameTest extends TestCase {
  public function testAddTurn() {
    $game = new Game(
      'randomtoken',
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 0)
      ),
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );

    for ($i = 1; $i <= 23; $i++) {
      $game->addTurn();
    }

    $this->assertEquals(23, $game->getTurn());
  }
}
