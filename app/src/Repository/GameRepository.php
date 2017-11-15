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

  public function findOpenMultiplayerGames() : Array {
    $qb = $this->em->createQueryBuilder();
    $qb->select('game.id')
      ->from('App:Game', 'game')
      ->where('game.isMultiplayer = true')
      ->andWhere('game.player2 IS NULL');
    return $qb->getQuery()->getResult();
  }

  public function remove(string $id) {
    $game = $this->em->getReference('App:Game', $id);
    $this->em->remove($game);
    $this->em->flush();
  }

  public function save(Game $game) {
    $this->em->persist($game);
    $this->em->flush();
  }
}
