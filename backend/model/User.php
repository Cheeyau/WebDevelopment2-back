<?php

namespace Model;

class User {
    
    function __construct(
        public int $id,
        public string $name,
        public string $password,
        public string $email_address,
        public int $user_roll,
        public string $registration
    ) {}
}