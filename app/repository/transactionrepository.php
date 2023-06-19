<?php

namespace Repositories;

use Models\Transaction;
use PDO;
use PDOException;
use DateTime;
use Repositories\Repository;

class TransactionRepository extends Repository {

    function __construct() {}

    // give user_id if not admin
    function getAll($offset = NULL, $limit = NULL, int $user = null):mixed {
        try {
            if(isset($user)) {
                $query = "SELECT `transaction`.`transaction_id` as id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` from `transaction` left join `user` on `transaction`.`user_id` = `user`.`user_id` where `transaction`.`user_id` = :id";
            } else {
                $query = "SELECT `transaction`.`transaction_id` as id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` from `transaction` left join `user` on `transaction`.`user_id` = `user`.`user_id`";
            }
            
            if (isset($limit) && isset($offset)) $query .= " LIMIT :limit OFFSET :offset ";
            
            $stmt = $this->connection->prepare($query);
            
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            
            if(isset($user)) $stmt->bindParam(':id', $user, PDO::PARAM_INT);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Model\Transaction");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // give user_id if not admin
    public function getById(int $id, int $user = null): mixed {
        try {
            if(isset($user)) {
                $query = "SELECT `transaction`.`transaction_id` as id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` from `transaction` left join `user` on `transaction`.`user_id` = `user`.`user_id` where `transaction`.`transaction_id` = :id and `transaction`.`user_id` = :user_id";
            } else {
                $query = "SELECT `transaction`.`transaction_id` as id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` from `transaction` left join `user` on `transaction`.`user_id` = `user`.`user_id` where `transaction`.`transaction_id` = :id";
            }
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if(isset($user)) $stmt->bindParam(':id', $user, PDO::PARAM_INT);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Model\Transaction");
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Transaction $transaction): mixed {
        $transaction->created = new DateTime();
        try {
            $stmt = $this->connection->prepare("INSERT into transaction () values (?,?,?,?)");

            $stmt->execute([$transaction->amount, $transaction->user_id, $transaction->created, $transaction->order_id, $transaction->status]);

            $transaction->id = $this->connection->lastInsertId();

            return $this->getById($transaction->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function updateStatus(Transaction $transaction, int $id): mixed {
        try {
            $stmt = $this->connection->prepare("UPDATE `transaction` set `status` = ? where `transaction_id` = ?");

            $stmt->execute([$transaction->status, $id]);

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}