<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entity\Player;

class PlayerRepository extends EntityRepository {
  private $em;

  public function __construct(EntityManager $em) {
    $this->em = $em;
  }

  public function findPlayerById(string $id) : Player {
    return $this->em->find('App:Player', $id);
  }

  public function save(Player $player) {
    $this->em->persist($player);
    $this->em->flush();
  }
}
