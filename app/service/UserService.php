<?php

namespace Service;

use Model\User;
use Repository\UserRepository;

class UserService {

    function __construct(
        private $repo = new UserRepository()
    ) {}

    public function checkUsernamePassword(string $name, string $password): mixed {
        return $this->repo->checkPassword($name, $password);
    }

    public function validateToken(string $token): bool {
        return $this->repo->checkJWT($token);
    }
    
    public function getUser(int $user_id): mixed {
        return $this->repo->getUser($user_id);
    }

    public function create(User $user): mixed {
        return $this->repo->create($user);
    }

    public function update(User $user, int $id): mixed {
        return $this->update($user, $id);
    }
}