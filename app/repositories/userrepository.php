<?php

namespace Repositories;

use DateTime;
use Models\User;
use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository {

    function __construct() {}

    public function checkUsernamePassword($name, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT `user_id`, `name`, `user_roll` FROM user WHERE username = :username");
            $stmt->bindParam(':username', $name);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // hash the password (currently uses bcrypt)
    private function hashPassword($password): mixed {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    private function verifyPassword($input, $hash): bool {
        return password_verify($input, $hash);
    }

    public function getById(int $id): mixed {
        try {
            $query = "SELECT * from `user` where `user`.`user_id` = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Model\User");
            
            return $stmt->fetch();;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(User $user): mixed {
        $user->registration = date_format(new DateTime(), "Y-m-d H:i:s");
        try {
            $stmt = $this->connection->prepare("INSERT into `order` (name, email_address, password, user_roll, registration) values (?,?,?,?,?)");

            $stmt->execute([$user->name, $user->emailAddress, $this->hashPassword($user->password), $user->userRoll, $user->registration]);

            $user->id = $this->connection->lastInsertId();

            return $this->getById($user->id);
        } catch (PDOException $e) {
            echo $e;
        }        
    }

    public function update(User $user, int $id): mixed {
        try {
            $stmt = $this->connection->prepare("UPDATE `user` set `name` = ?, `email_address` = ?, `password` = ?, `user_roll` = ? where `user_id` = ?");

            $stmt->execute([$user->name, $user->emailAddress, $user->password, $user->userRoll, $id]);

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}