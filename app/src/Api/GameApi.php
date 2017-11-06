<?php

namespace App\Api;

use App\Api\GameSerializer;
use GuzzleHttp\Client;

class GameApi {
  private $client;
  private $serializer;

  public function __construct(Client $gameApiClient) {
    $this->client = $gameApiClient;
    $this->serializer = new GameSerializer();
  }

  public function new($size) {
    $response = $this->client->get('/new', [
      'query' => 'size=' . $size
    ]);
    return $this->serializer->deserialize($response->getBody());
  }

  public function move($grid, $coords) {
    $gridJson = $this->serializer->serialize($grid);
    $coordsJson = json_encode($coords);
    $response = $this->client->post('/move', [
      'json' => [
        'grid' => $gridJson,
        'coords' => $coordsJson
      ]
    ]);
    return $this->serializer->deserialize($response->getBody());
  }

  public function suggest($grid, $initialGrid) {
    $gridJson = $this->serializer->serialize($grid);
    $initialGridJson = $this->serializer->serialize($initialGrid);
    $response = $this->client->get('/suggest', [
      'json' => [
        'grid' => $gridJson,
        'initial_grid' => $initialGridJson
    ]]);
    return $this->serializer->deserialize($response->getBody());
  }
}
