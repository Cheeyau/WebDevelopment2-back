<?php

namespace Repositories;

use Models\Paginator;
use Models\Transaction;
use PDO;
use PDOException;
use DateTime;
use Repositories\Repository;

class TransactionRepository extends Repository {

    private string $get_query = "SELECT 
        `Transaction`.`id`, 
        `Transaction`.`total`, 
        `Transaction`.`user_id`, 
        `User`.`name`, 
        `Transaction`.`created`, 
        `Transaction`.`status`
        FROM `Transaction` LEFT JOIN `User` ON `Transaction`.`user_id` = `User`.`id` ";

    function getAll(Paginator $pages, int $user_id, int $user_roll) {
        try {
            if ($user_roll === 0 || !isset($pages->user_id)) { 
                $stmt = $this->connection->prepare($this->get_query . "WHERE `Transaction`.`user_id` = :id LIMIT :limit OFFSET :offset");
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            } else if (isset($pages->user_id) && $user_roll >= 0) {
                $stmt = $this->connection->prepare($this->get_query . "WHERE `Transaction`.`user_id` = :id LIMIT :limit OFFSET :offset");
                $stmt->bindParam(':id', $pages->user_id, PDO::PARAM_INT); 
            } else {
                $stmt = $this->connection->prepare($this->get_query . "LIMIT :limit OFFSET :offset");
            }
            
            $stmt = $this->setPaginator($stmt, $pages);
            
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Transaction");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getById(int $id, int $user_id) {
        try {
            if(isset($user_id)) {
                $stmt = $this->connection->prepare($this->get_query . "WHERE `Transaction`.`id` = :id AND `Transaction`.`user_id` = :user_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            } else {
                $stmt = $this->connection->prepare($this->get_query . "WHERE `Transaction`.`id` = :id");
            }
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

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
            $stmt = $this->connection->prepare("UPDATE `Transaction` set `status` = ? where `id` = ?");

            $stmt->execute([$transaction->status, $id]);

            return $this->getById($id, $transaction->user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
