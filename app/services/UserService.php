<?php

namespace Services;

use Models\User;
use Repositories\UserRepository;

class UserService {

    private $repo;

    function __construct() {
        $this->repo = new UserRepository();
    }

    public function checkUsernamePassword(string $name, string $password) {
        return $this->repo->checkUsernamePassword($name, $password);
    }
    
    public function getUser(int $user_id) {
        return $this->repo->getById($user_id);
    }

    public function create(User $user) {
        return $this->repo->create($user);
    }

    public function update(User $user, int $id) {
        return $this->repo->update($user, $id);
    }
}