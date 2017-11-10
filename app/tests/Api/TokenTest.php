<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Api\Token;

class TokenTest extends TestCase {
  public function testGenerateDefaultLen() {
    $token = Token::generate();
    $this->assertEquals(strlen($token), Token::TOKEN_LENGTH * 2);
    
  }
  
  public function testGenerateLenZero() {
    $token = Token::generate(0);
    $this->assertEquals(strlen($token), Token::TOKEN_LENGTH * 2);
  }
}
