<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Api\GameSerializer;
use App\Game\Game;

class GameSerializerTest extends TestCase {
  private function buildGame() {
    $grid = [
      [1, 2, 3, 4],
      [5, 6, 7, 8],
      [9, 10, 0, 11],
      [13, 14, 15, 12],
    ];
    $initialGrid = [
      [1, 2, 3, 4],
      [5, 6, 7, 8],
      [9, 10, 11, 12],
      [13, 14, 15, 0],
    ];
    $game =new Game();
    $game->setGrid($grid);
    $game->setInitialGrid($initialGrid);
    return $game;
  }

  private function buildGameJson() {
    return '{"grid":[[1,2,3,4],[5,6,7,8],[9,10,0,11],[13,14,15,12]],"initialGrid":[[1,2,3,4],[5,6,7,8],[9,10,11,12],[13,14,15,0]]}';
  }
 
  public function testSerialize() {
    $gameSerializer = new GameSerializer();

    $game = $this->buildGame();
    $expectedGameJson = $this->buildGameJson();
    $gameJson = $gameSerializer->serialize($game);

    $this->assertEquals($gameJson, $expectedGameJson);
  }

  public function testDeserialize() {
    $gameSerializer = new GameSerializer();

    $expectedGame = $this->buildGame();
    $gameJson = $this->buildGameJson();
    $game = $gameSerializer->deserialize($gameJson);

    $this->assertEquals($game, $expectedGame);
  }
}
