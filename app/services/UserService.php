<?php

namespace Services;

use Models\User;
use Repositories\UserRepository;

class UserService {

    private $repo;

    function __construct() {
        $this->repo = new UserRepository();
    }

    public function checkUsernamePassword(string $name, string $password): mixed {
        return $this->repo->checkUsernamePassword($name, $password);
    }
    
    public function getUser(int $user_id): mixed {
        return $this->repo->getById($user_id);
    }

    public function create(User $user): mixed {
        return $this->repo->create($user);
    }

    public function update(User $user, int $id): mixed {
        return $this->update($user, $id);
    }
}