<?php

namespace Repositories;

use Models\Paginator;
use Models\Transaction;
use PDO;
use PDOException;
use DateTime;
use Repositories\Repository;

class TransactionRepository extends Repository {

    // give user_id if not admin
    function getAll(Paginator $pages, int $user) {
        try {
            if(isset($user)) {
                $query = "SELECT 
                `Transaction`.`id`, 
                `Transaction`.`total`, |
                `Transaction`.`user_id`, 
                `User`.`name`, 
                `Transaction`.`created`, 
                `Transaction`.`status` 
                FROM `Transaction` LEFT JOIN `User` ON `Transaction`.`user_id` = `User`.`id` 
                WHERE `Transaction`.`user_id` = :id  
                LIMIT :limit OFFSET :offset";
            } else {
                $query = "SELECT 
                `Transaction`.`id`, 
                `Transaction`.`total`, 
                `Transaction`.`user_id`, 
                `User`.`name`, 
                `Transaction`.`created`, 
                `Transaction`.`status` 
                FROM `Transaction` 
                LEFT JOIN `User` ON `Transaction`.`user_id` = `User`.`user_id` 
                LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->connection->prepare($query);
            
            $stmt = $this->setPaginator($stmt, $pages);
            
            if(isset($user)) $stmt->bindParam(':id', $user, PDO::PARAM_INT);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Transaction");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // give user_id if not admin
    public function getById(int $id, int $user_id) {
        try {
            if(isset($user)) {
                $query = "SELECT 
                `Transaction`.`id`, 
                `Transaction`.`total`, 
                `Transaction`.`user_id`, 
                `user`.`name`, 
                `Transaction`.`created`, 
                `Transaction`.`status` 
                FROM `Transaction` LEFT JOIN `User` ON `Transaction`.`user_id` = `User`.`id` 
                WHERE `Transaction`.`id` = :id AND `Transaction`.`user_id` = :user_id";
            } else {
                $query = "SELECT 
                `Transaction`.`id`, 
                `Transaction`.`total`, 
                `Transaction`.`user_id`, 
                `User`.`name`, 
                `Transaction`.`created`, 
                `Transaction`.`status` 
                FROM `Transaction` LEFT JOIN `User` ON `Transaction`.`user_id` = `User`.`id` 
                WHERE `Transaction`.`id` = :id";
            }
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if(isset($user)) $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Transaction");
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Transaction $transaction) {
        $now = new DateTime();
        $transaction->created = $now->format('Y-m-d H:i:s');

        try {
            $stmt = $this->connection->prepare("INSERT into `Transaction` (total, user_id, created, status) values (?,?,?,?)");

            $stmt->execute([$transaction->total, $transaction->user_id, $transaction->created, $transaction->status]);

            $transaction->id = $this->connection->lastInsertId();

            return $this->getById($transaction->id, $transaction->user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function updateStatus(Transaction $transaction, int $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE `transaction` set `status` = ? where `transaction_id` = ?");

            $stmt->execute([$transaction->status, $id]);

            return $this->getById($id, $transaction->user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
