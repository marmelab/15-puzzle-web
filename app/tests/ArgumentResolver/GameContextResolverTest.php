<?php

namespace Tests\App\ArgumentResolver;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use App\ArgumentResolver\GameContext;
use App\ArgumentResolver\GameContextResolver;
use App\Authentication\CookieAuthManager;
use App\Entity\Game;
use App\Repository\GameRepository;

class GameContextResolverTest extends TestCase {
  private function createGameContextResolver() {
    $gameStub = $this->createMock(Game::class);
    $repoStub = $this->createMock(GameRepository::class);    
    $repoStub->method('findGameById')->willReturn($gameStub);

    return new GameContextResolver($repoStub);
  }

  public function testSupportsShouldReturnTrue() {
    $gameContextResolver = $this->createGameContextResolver();

    $requestStub = $this->createMock(Request::class);
    $argStub = $this->createMock(ArgumentMetadata::class);
    $argStub->method('getType')->willReturn(GameContext::class);

    $result = $gameContextResolver->supports($requestStub, $argStub);
    $this->assertEquals($result, true);
  }

  public function testSupportsShouldReturnFalse() {
    $gameContextResolver = $this->createGameContextResolver();

    $requestStub = $this->createMock(Request::class);
    $argStub = $this->createMock(ArgumentMetadata::class);
    $argStub->method('getType')->willReturn(Game::class);

    $result = $gameContextResolver->supports($requestStub, $argStub);
    $this->assertEquals($result, false);
  }

  public function testResolveShouldBeOwner() {
    $this->markTestSkipped();

    $gameContextResolver = $this->createGameContextResolver();

    $cookieAuthManagerStub = $this->createMock(CookieAuthManager::class);
    $cookieAuthManagerStub->method('isOwner')->willReturn(true);

    $requestStub = $this->createMock(Request::class);
    $requestStub->method('get')->willReturn('mockedId');

    $argStub = $this->createMock(ArgumentMetadata::class);

    $resultContext = $gameContextResolver->resolve($requestStub, $argStub)->next()->current();
    $this->assertEquals($resultContext->getGame(), $game);
    $this->assertEquals($resultContext->getIsOwner(), true);
  }

  public function testResolveShouldNotBeOwner() {
    $this->markTestSkipped();

    $gameContextResolver = $this->createGameContextResolver();

    $cookieAuthManagerStub = $this->createMock(CookieAuthManager::class);
    $cookieAuthManagerStub->method('isOwner')->willReturn(false);

    $requestStub = $this->createMock(Request::class);
    $requestStub->method('get')->willReturn('mockedId');

    $argStub = $this->createMock(ArgumentMetadata::class);

    $resultContext = $gameContextResolver->resolve($requestStub, $argStub)->next()->current();

    $this->assertEquals($resultContext->getGame(), $game);
    $this->assertEquals($resultContext->getIsOwner(), true);
  }
}
