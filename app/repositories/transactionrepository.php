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
                $query = "SELECT `transaction`.`id` AS id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` FROM `transaction` LEFT JOIN `user` ON `transaction`.`user_id` = `user`.`user_id` WHERE `transaction`.`user_id` = :id  LIMIT :limit OFFSET :offset";
            } else {
                $query = "SELECT `transaction`.`id` AS id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` FROM `transaction` LEFT JOIN `user` ON `transaction`.`user_id` = `user`.`user_id` LIMIT :limit OFFSET :offset";
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
    public function getById(int $id, int $user = null) {
        try {
            if(isset($user)) {
                $query = "SELECT `transaction`.`id` AS id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` FROM `transaction` LEFT JOIN `user` ON `transaction`.`user_id` = `user`.`user_id` WHERE `transaction`.`id` = :id AND `transaction`.`user_id` = :user_id";
            } else {
                $query = "SELECT `transaction`.`id` AS id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` FROM `transaction` LEFT JOIN `user` ON `transaction`.`user_id` = `user`.`user_id` WHERE `transaction`.`id` = :id";
            }
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if(isset($user)) $stmt->bindParam(':id', $user, PDO::PARAM_INT);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Transaction");
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Transaction $transaction) {
        $transaction->created = new DateTime();
        try {
            $stmt = $this->connection->prepare("INSERT into transaction () values (?,?,?,?)");

            $stmt->execute([$transaction->amount, $transaction->id, $transaction->created, $transaction->order_id, $transaction->status]);

            $transaction->id = $this->connection->lastInsertId();

            return $this->getById($transaction->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function updateStatus(Transaction $transaction, int $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE `transaction` set `status` = ? where `transaction_id` = ?");

            $stmt->execute([$transaction->status, $id]);

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
