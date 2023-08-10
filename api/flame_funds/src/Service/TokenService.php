<?php

namespace App\Service;

use PhpParser\Node\Scalar\String_;

class TokenService
{
    private $token;
    private $tokenHeader;
    private $tokenPayload;

    public function __construct(String $token)
    {
        $this->token = $token;
        $tokenParts = explode(".", $this->token);
        $this->tokenHeader = base64_decode($tokenParts[0]);
        $this->tokenPayload = base64_decode($tokenParts[1]);
    }

    /**
     * @param String $token
     * @return String
     */
    public function getUsername(): String
    {
        $jwtPayload = json_decode($this->tokenPayload);
dd($jwtPayload);
        return $jwtPayload->username;
    }
}