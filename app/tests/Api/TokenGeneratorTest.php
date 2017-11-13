<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Api\TokenGenerator;

class TokenTest extends TestCase {
  public function testGenerateDefaultLen() {
    $token = new TokenGenerator();
    $token = $token->generate();
    $this->assertEquals(strlen($token), TokenGenerator::TOKEN_LENGTH * 2);
  }

  public function testGenerateLenZero() {
    $tokenGenerator = new TokenGenerator();
    $token = $tokenGenerator->generate(0);
    $this->assertEquals(strlen($token), TokenGenerator::TOKEN_LENGTH * 2);
  }
}
