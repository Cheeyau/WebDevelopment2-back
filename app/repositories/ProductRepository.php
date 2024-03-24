<?php

namespace Repositories;

use Models\Product;
use PDO;
use PDOException;
use Repositories\Repository;

class ProductRepository extends Repository {
    function getAll($pages) {
        try {
            $stmt = $this->connection->prepare("SELECT `id`, `price`, `name`, `image`, `description` FROM `Product` ORDER BY `id` LIMIT :limit OFFSET :offset");
            
            $stmt = $this->setPaginator($stmt, $pages);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Product");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getById(int $id) {
        try {
            $stmt = $this->connection->prepare("SELECT `id`, `price`, `name`, `image`, `description` FROM `Product` WHERE `Product`.`id` = ?");
            $stmt->execute([$id]);

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Product");
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Product $product) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO `Product` (`price`, `name`, `image`, `description`) VALUES (?,?,?,?)");

            $stmt->execute([$product->price, $product->name, $product->image, $product->description]);

            $product->id = $this->connection->lastInsertId();

            return $this->getById($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function update(Product $product, int $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE `Product` SET `price` = ?, `name` = ?, `image` = ?, `description` = ? WHERE `id` = ?");

            $stmt->execute([$product->price, $product->name, $product->image, $product->description, $id]);

            return $this->getById($id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete(int $id) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM `Product` WHERE `id` = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo $e;
        };
    }
}