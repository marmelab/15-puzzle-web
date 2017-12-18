<?php

namespace Tests\App\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Player;

class PlayerTest extends TestCase {
  public function testAddTurn() {
    $player = new Player(
      'randomtoken',
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );

    for ($i = 1; $i <= 23; $i++) {
      $player->addTurn();
    }

    $this->assertEquals(23, $player->getTurn());
  }

  public function testJsonSerialize() {
    $player = new Player(
      'randomtoken',
      array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      )
    );
    $player->setId(18);

    $expectedPlayerJson = [
      'id' => 18,
      'currentGrid' => array(
        array(1, 2, 3),
        array(4, 0, 5),
        array(7, 8, 6)
      ),
      'turn' => 0
    ];

    $this->assertEquals($expectedPlayerJson, $player->jsonSerialize());
  }
}
