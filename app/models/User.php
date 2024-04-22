<?php

namespace Models;

class User {
    
    public int $id;
    public string $name;
    public string $password;
    public string $email_address;
    public int $user_role;
    public string $registration;
}