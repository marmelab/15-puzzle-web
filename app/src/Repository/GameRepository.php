<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entity\Game;

class GameRepository extends EntityRepository {
  private $em;

  public function __construct(EntityManager $em) {
    $this->em = $em;
  }

  public function findGameById(string $id) : Game {
    return $this->em->find('App:Game', $id);
  }
  
  public function save(Game $game) {
    $this->em->persist($game);
    $this->em->flush();
  }
}
