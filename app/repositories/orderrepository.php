<?php

namespace Repositories;

use DateTime;
use Models\OrderDetail;
use Models\Order;
use PDO;
use PDOException;
use Repositories\Repository;

class OrderRepository extends Repository {
    
    function __construct() {}
    
    // offset and limit by order and give user_id if not admin
    public function getAll($offset = null, $limit = null, int $user_id) {
        try {
            $query = "SELECT `order`.`order_id` as id, `order`.`user_id`, `order`.`name`, `order`.`email_address`, `order`.`created`, `order_detail`.`order_detail_id`, `order_detail`.`product_id`, `product`.`name` as product_name, `order_detail`.`amount`, `product`.`price` 
                from `order`
                left join `order_detail` on `order`.`order_id` = `order_detail`.`order_id`
                left join `product` on `product`.`product_id` = `order_detail`.`product_id`
                where `order`.`user_id` = :id";
            if (isset($limit) && isset($offset)) $query .= " LIMIT :limit OFFSET :offset ";
            
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            return $this->convertToClass($stmt);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function convertToClass($stmt): array {
        $orders = [];
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $last_key = $orders[array_key_last($orders)];
            if($orders[$last_key]->id !== $row['id'] || !$orders) {
                $order = new Order(
                    $row['id'],
                    $row['user_id'],
                    $row['name'],
                    $row['email_address'],
                    $row['created'],
                    [$this->setOrderDetail($row)]
                );
                $orders[] = $order;
            } else {
                $orders[$last_key]->items[] = $this->setOrderDetail($row);
            }
        }
        return $orders;
    }

    private function setOrderDetail($row): OrderDetail {
        return new OrderDetail(
            $row['order_detail_id'],
            $row['product_id'],
            $row['product_name'],
            $row['amount'],
            $row['price'],
        );
    }

    // give user_id if not admin
    public function getById(int $id, ): mixed {
        try {
            $query = "SELECT `order`.`order_id` as id, `order`.`user_id`, `order`.`name`, `order`.`email_address`, `order`.`created`, `order_detail`.`order_detail_id`, `order_detail`.`product_id`, `product`.`name` as product_name, `order_detail`.`amount`, `product`.`price` from `order` left join `order_detail` on `order`.`order_id` = `order_detail`.`order_id` left join `product` on `product`.`product_id` = `order_detail`.`product_id` where `order`.`order_id` = :id";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $this->convertToClass($stmt)[0];
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Order $order): mixed {
        $order->created = new DateTime();

        try {
            $stmt = $this->connection->prepare("INSERT into `order` (user_id, name, email_address, created) values (?,?,?,?)");

            $stmt->execute([$order->user_id, $order->name, $order->email_address, $order->created]);

            $order->id = $this->connection->lastInsertId();

            $stmt = $this->connection->prepare("INSERT into `order_detail` (order_id, product_id, amount) values (?,?,?)");
            foreach($order->items as $item) {
                $stmt->execute([$order->user_id, $order->name, $order->email_address, $order->created]);
            }

            return $this->getById($order->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function update(Order $order, int $id): mixed {
        try {
            $stmt = $this->connection->prepare("UPDATE `order` set `user_id` = ?, `name` = ?, `email_address` = ?, `created` = ? where `order_id` = ?");

            $stmt->execute([$order->user_id, $order->name, $order->email_address, $order->created, $id]);

            $stmt = $this->connection->prepare("UPDATE `order_detail` set `order_id` = ?, `product_id` = ?, `amount` = ? where `order_detail_id` = ?");

            foreach($order->items as $item) {
                $stmt->execute([$order->id, $item->product_id, $item->amount, $id]);                
            }

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function delete(int $id): mixed {
        try {
            $stmt = $this->connection->prepare("DELETE from `order` where `order_id` = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt = $this->connection->prepare("DELETE from `order_detail` where `order_id` = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }
}