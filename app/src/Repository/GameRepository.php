<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entity\GameEntity;

class GameRepository extends EntityRepository {
  private $em;

  public function __construct(EntityManager $em) {
    $this->em = $em;
  }

  public function findGameEntityById(string $id) : GameEntity {
    return $this->em->find('App:GameEntity', $id);
  }
  
  public function save(GameEntity $game) {
    $this->em->persist($game);
    $this->em->flush();
  }
}
