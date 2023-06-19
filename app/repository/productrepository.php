<?php

namespace Repositories;

use Models\Product;
use PDO;
use PDOException;
use Repositories\Repository;

class ProductRepository extends Repository {

    function __construct() {}

    function getAll($offset = NULL, $limit = NULL):mixed {
        try {
            $query = "SELECT * from `product`";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Model\Product");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getById(int $id): mixed {
        try {
            $query = "SELECT * from `product` where `product`.`product_id` = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Model\Product");
            
            return $stmt->fetch();;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Product $product): mixed {
        try {
            $stmt = $this->connection->prepare("INSERT into `product` (`price`, `name`, `image`, `description`) values (?,?,?,?)");

            $stmt->execute([$product->price, $product->name, $product->image_path, $product->description]);

            $product->id = $this->connection->lastInsertId();

            return $this->getById($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    
    public function update(Product $product, int $id): mixed {
        try {
            $stmt = $this->connection->prepare("UPDATE `product` set `price` = ?, `name` = ?, `image` = ?, `description` = ? where `product_id` = ?");

            $stmt->execute([$product->price, $product->name, $product->image_path, $product->description, $id]);

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete(int $id): mixed {
        try {
            $stmt = $this->connection->prepare("DELETE from `product` where `product_id` = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        };
    }
}