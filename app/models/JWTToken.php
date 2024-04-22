<?php

namespace Models;

class JWTToken {
    public string $iss;
    public string $aud;
    public int $iat;
    public int $nbf;
    public int $exp;
    public User $user;

    function __construct($decoded) {
        $this->iss = $decoded->iss;
        $this->aud = $decoded->aud;
        $this->iat = $decoded->iat;
        $this->nbf = $decoded->nbf;
        $this->exp = $decoded->exp;
        
        $user = new User();
        $user->id = $decoded->data->id;
        $user->name = $decoded->data->name;
        $user->user_role = $decoded->data->user_role;
        
        $this->user = $user;
    }
}
