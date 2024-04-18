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

    // offset and limit by order and give user_id if not admin
    public function getAll(Paginator $pages, int $user_id) {
        try {            
            $stmt = $this->connection->prepare("SELECT 
                `Order`.`id` as id, 
                `Order`.`user_id`, 
                `Order`.`name`, 
                `Order`.`email_address`, 
                `Order`.`created`, 
                `Order_detail`.`product_id`, 
                `Product`.`name` as product_name, 
                `Order_detail`.`amount`, 
                `Product`.`price` 
                from `Order`
                left join `Order_detail` on `Order`.`id` = `Order_detail`.`order_id`
                left join `Product` on `Product`.`id` = `Order_detail`.`product_id`
                where `Order`.`user_id` = :id LIMIT :limit OFFSET :offset");
            
            $stmt = $this->setPaginator($stmt, $pages);

            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            
            $stmt->execute();

            return $this->convertToClass($stmt);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    private function convertToClass($stmt) {
        $orders = [];
        $lastKey = 0;
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            if($orders[$lastKey]->id !== $row['id'] || !$orders) {
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
                $orders[$lastKey]->items[] = $this->setOrderDetail($row);
            }
            $lastKey = $row['id'] -1;
        }
        return $orders;
    }

    private function setOrderDetail($row): OrderDetail {
        return new OrderDetail(
            $row['product_id'],
            $row['product_name'],
            $row['amount'],
            $row['price']
        );
    }

    // give user_id if not admin
    public function getById(int $id, ) {
        try {
            $stmt = $this->connection->prepare("SELECT 
                `Order`.`id` as id, 
                `Order`.`user_id`, 
                `Order`.`name`, 
                `Order`.`email_address`, 
                `Order`.`created`, 
                `Order_detail`.`product_id`, 
                `Product`.`name` as product_name, 
                `Order_detail`.`amount`, 
                `Product`.`price` 
                from `Order`
                left join `Order_detail` on `Order`.`id` = `Order_detail`.`order_id`
                left join `Product` on `Product`.`id` = `Order_detail`.`product_id`
                where `Order`.`id` = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $this->convertToClass($stmt)[0];
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Order $order){
        $order->created = (string) new DateTime();

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

    public function delete(int $id) {
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