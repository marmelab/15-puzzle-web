<?php

namespace App\Api;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use App\Game\Game;

class GameSerializer {
  private $serializer;

  public function __construct() {
    $encoders = array(new JsonEncoder());
    $normalizers = array(new ObjectNormalizer(), new GetSetMethodNormalizer(), new ArrayDenormalizer()); 
    $this->_serializer = new Serializer($normalizers, $encoders);
  }

  public function deserialize($gameJson) {
    return $this->_serializer->deserialize($gameJson, Game::class, 'json');
  }

  public function serialize($game) {
    return $this->_serializer->serialize($game, 'json');
  }
}
