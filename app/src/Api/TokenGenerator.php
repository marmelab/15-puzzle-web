<?php

namespace App\Api;

class TokenGenerator {
  public const TOKEN_LENGTH = 10;

  public function generate(int $tokenLength = self::TOKEN_LENGTH) : string {
    if ($tokenLength <= 0) {
      $tokenLength = self::TOKEN_LENGTH;
    }
    return bin2hex(random_bytes($tokenLength));
  }
}
