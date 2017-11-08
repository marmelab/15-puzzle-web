<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Serializer;
use App\Entity\GameEntity;
use App\Game\Game;

class GameRepository extends EntityRepository {
  private $em;

  public function __construct(EntityManager $em, Serializer $serializer) {
    $this->em = $em;
    $this->serializer = $serializer;
  }

  private function findGameEntityById(string $id) : GameEntity {
    return $this->em->find('App:GameEntity', $id);
  }

  public function flush() {
    $this->em->flush();
  }

  public function createGame(Game $game) : GameEntity {
    $gameSerialized = $this->serializer->serialize($game, 'json');
    $gameEntity = new GameEntity($gameSerialized);
    $this->em->persist($gameEntity);
    return $gameEntity;
  }

  public function updateGame(string $id, Game $game) : GameEntity {
    $gameSerialized = $this->serializer->serialize($game, 'json');
    $gameEntity = $this->em->find('App:GameEntity', $id);
    $gameEntity->setGame($gameSerialized);
    $this->em->persist($gameEntity);
    return $gameEntity;
  }

  public function findGameById(string $id) : Game {
    $gameEntity = $this->findGameEntityById($id);
    return $this->serializer->deserialize($gameEntity->getGame(), Game::class, 'json');
  }
}
