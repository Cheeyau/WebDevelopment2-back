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

    private string $get_order_query = "SELECT 
        `Order`.`id` as id, 
        `Order`.`user_id`, 
        `User`.`name`, 
        `User`.`email_address`, 
        `Order`.`created`, 
        `Order`.`status`, 
        `Order_detail`.`product_id`, 
        `Order_detail`.`id` as order_detail_id, 
        `Product`.`name` as product_name, 
        `Order_detail`.`amount`, 
        `Product`.`price`,
        `Transaction`.`status` as transaction_status,
        `Transaction`.`total`
        from `Order`
        left join `Order_detail` on `Order`.`id` = `Order_detail`.`order_id`
        left join `Product` on `Product`.`id` = `Order_detail`.`product_id`
        left join `User` on `User`.`id` = `Order`.`user_id`
        left join `Transaction` on `Transaction`.`id` = `Order`.`transaction_id` ";
    
    public function getAll(Paginator $pages, int $user_id, int $user_role) {
        try {            
            
            if ($user_role === 0 || !isset($pages->user_id)) { 
                $stmt = $this->connection->prepare($this->get_order_query . "where `Order`.`user_id` = :id ORDER BY `created` DESC LIMIT :limit OFFSET :offset");
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            } else if (isset($pages->user_id) && $user_role >= 0) {
                $stmt = $this->connection->prepare($this->get_order_query . "where `Order`.`user_id` = :id ORDER BY `created` DESC LIMIT :limit OFFSET :offset");
                $stmt->bindParam(':id', $pages->user_id, PDO::PARAM_INT);
            } else {
                $stmt = $this->connection->prepare($this->get_order_query . "LIMIT :limit OFFSET :offset");
            }
            $stmt = $this->setPaginator($stmt, $pages);
            
            $stmt->execute();

            return $this->convertToClass($stmt, $user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    

    public function getById(int $id) {
        try {
            $stmt = $this->connection->prepare($this->get_order_query . "where `Order`.`id` = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $this->convertToClass($stmt, null);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    private function convertToClass($stmt, $user_id) {
        $orders = [];
        $lastKey = 0;
        if (!is_null($user_id)) {
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                if(!$orders || $orders[$lastKey]->id !== $row['id']) {
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
            $row['transaction_status'],
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
            $stmt = $this->connection->prepare("INSERT into `Order` (user_id, transaction_id, created, status) values (?,?,?,?)");

            $stmt->execute([$order->user_id, $order->transaction_id, $order->created, $order->status]);

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

    public function updateStatus(Order $order, int $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE `Order` set `status` = ? where `id` = ?");

            $stmt->execute([$order->status, $id]);

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}