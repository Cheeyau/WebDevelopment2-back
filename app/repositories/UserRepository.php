<?php

namespace Repositories;

use DateTime;
use Models\User;
use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository {

    public function checkUsernamePassword($name, $password)
    {
        try {
            // retrieve the user with the given username
            $user = $this->getByName($name);
            if (!$user) return false;

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);
            if (!$result) return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getByName(string $name) {
        try { 
            $stmt = $this->connection->prepare("SELECT `id`, `name`, `user_roll`, `password` FROM User WHERE `name` = ?");
            $stmt->execute([$name]);

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // hash the password (currently uses bcrypt)
    private function hashPassword($password): mixed {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    private function verifyPassword($password, $hash): bool {
        return password_verify($password, $hash);
    }

    public function getById(int $id) {
        try {
            $stmt = $this->connection->prepare("SELECT * from `User` where `User`.`id` = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\User");
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(User $user) {
        $user->registration = date_format(new DateTime(), "Y-m-d H:i:s");
        try {
            $stmt = $this->connection->prepare("INSERT into `User` (name, email_address, password, user_roll, registration) values (?,?,?,?,?)");

            $stmt->execute([$user->name, $user->email_address, $this->hashPassword($user->password), $user->user_roll, $user->registration]);

            $user->id = $this->connection->lastInsertId();

            return $this->getById($user->id);
        } catch (PDOException $e) {
            echo $e;
        }        
    }

    public function update(User $user, int $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE `User` set `name` = ?, `email_address` = ?, `password` = ?, `user_roll` = ? where `User`.`id` = ?");

            $stmt->execute([$user->name, $user->email_address, $user->password, $user->user_roll, $id]);

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    function getAll() {
        try {
            $stmt = $this->connection->prepare("SELECT `id`, `name`, `email_address`, `registration` FROM `User`");
            
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\User");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }
}