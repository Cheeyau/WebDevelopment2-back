<?php

namespace Repositories;

use DateTime;
use Models\OrderDetail;
use Models\Order;
use Models\Paginator;
use PDO;
use PDOException;
use Repositories\Repository;

class OrderRepository extends Repository {
    public function getAll(Paginator $pages, int $user_id) {
        try {            
            $stmt = $this->connection->prepare("SELECT 
                `Order`.`id` as id, 
                `Order`.`user_id`, 
                `User`.`name`, 
                `User`.`email_address`, 
                `Order`.`created`, 
                `Order_detail`.`product_id`, 
                `Order_detail`.`id` as order_detail_id, 
                `Product`.`name` as product_name, 
                `Order_detail`.`amount`, 
                `Product`.`price`,
                `Transaction`.`status`,
                `Transaction`.`total`
                from `Order`
                left join `Order_detail` on `Order`.`id` = `Order_detail`.`order_id`
                left join `Product` on `Product`.`id` = `Order_detail`.`product_id`
                left join `User` on `User`.`id` = `Order`.`user_id`
                left join `Transaction` on `Transaction`.`id` = `Order`.`transaction_id`
                where `Order`.`user_id` = :id LIMIT :limit OFFSET :offset");
            
            $stmt = $this->setPaginator($stmt, $pages);

            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            
            $stmt->execute();

            return $this->convertToClass($stmt, $user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getById(int $id) {
        try {
            $stmt = $this->connection->prepare("SELECT 
                `Order`.`id` as id, 
                `Order`.`user_id`, 
                `User`.`name`, 
                `User`.`email_address`, 
                `Order`.`created`, 
                `Order_detail`.`product_id`, 
                `Order_detail`.`id` as order_detail_id, 
                `Product`.`name` as product_name, 
                `Order_detail`.`amount`, 
                `Product`.`price`,
                `Transaction`.`status`,
                `Transaction`.`total`
                from `Order`
                left join `Order_detail` on `Order`.`id` = `Order_detail`.`order_id`
                left join `Product` on `Product`.`id` = `Order_detail`.`product_id`
                left join `User` on `User`.`id` = `Order`.`user_id`
                left join `Transaction` on `Transaction`.`id` = `Order`.`transaction_id`
                where `Order`.`id` = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $this->convertToClass($stmt, $user_id = null);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    private function convertToClass($stmt, $user_id) {
        $orders = [];
        $lastKey = 0;
        if (!is_null($user_id)) {
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                if($orders[$lastKey]->id !== $row['id'] || !$orders) {
                    $order = $this->setOrder($row);
                    $orders[] = $order;
                } else {
                    $orders[$lastKey]->items[] = $this->setOrderDetail($row);
                }
                $lastKey = array_key_last($orders);
            }
        } else {
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                if(!$orders) {
                    $order = $this->setOrder($row);
                    $orders[] = $order;
                } else {
                    $orders[0]->items[] = $this->setOrderDetail($row);
                }
            }
        }
        return $orders;
    }
    private function setOrder($row) { 
        return Order::create(
            $row['id'],
            $row['user_id'],
            $row['name'],
            $row['email_address'],
            $row['created'],
            [$this->setOrderDetail($row)],
            $row['total'],
            $row['status']
        );
    }

    private function setOrderDetail($row): OrderDetail {
        return OrderDetail::get(
            $row['order_detail_id'],
            $row['product_id'],
            $row['product_name'],
            $row['amount'],
            $row['price']
        );
    }

    public function create(Order $order){
        $now = new DateTime();
        $order->created = $now->format('Y-m-d H:i:s');

        try {
            $stmt = $this->connection->prepare("INSERT into `Order` (user_id, transaction_id, created) values (?,?,?)");

            $stmt->execute([$order->user_id, $order->transaction_id, $order->created]);

            $order->id = $this->connection->lastInsertId();

            foreach ($order->items as $item) {
                $item->order_id = $order->id;
            }

            $stmt = $this->connection->prepare("INSERT into `Order_detail` (order_id, product_id, amount) values (?,?,?)");
            foreach($order->items as $item) {
                $stmt->execute([$item->order_id, $item->product_id, $item->amount]);
            }

            return $this->getById($order->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function update(Order $order, int $id) {
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
}