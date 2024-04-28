<?php

namespace Repositories;

use Models\Product;
use PDO;
use PDOException;
use Repositories\Repository;

class ProductRepository extends Repository {
    function getAll($pages) {
        try {
            if (isset($pages->category) && $pages->category > 0) {
                $query = "SELECT `id`, `price`, `name`, `image`, `description`, `category_id` FROM `Product` WHERE `Product`.`category_id` = :category_id ORDER BY `id` LIMIT :limit OFFSET :offset";
            } else {
                $query = "SELECT `id`, `price`, `name`, `image`, `description`, `category_id` FROM `Product` ORDER BY `id` LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->connection->prepare($query);
            
            $stmt = $this->setPaginator($stmt, $pages);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Product");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }
    function getCategories() {
        try {
            $stmt = $this->connection->prepare("SELECT `id`, `name` FROM `Category`");
            
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Category");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getById(int $id) {
        try {
            $stmt = $this->connection->prepare("SELECT `id`, `price`, `name`, `image`, `description`, `category_id` FROM `Product` WHERE `Product`.`id` = ?");
            $stmt->execute([$id]);

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Models\Product");
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function create(Product $product) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO `Product` (`price`, `name`, `image`, `description`, `category_id` ) VALUES (?,?,?,?,?)");

            $stmt->execute([$product->price, $product->name, $product->image, $product->description, $product->category_id]);

            $product->id = $this->connection->lastInsertId();

            return $this->getById($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function update(Product $product, int $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE `Product` SET `price` = ?, `name` = ?, `image` = ?, `description` = ?, `category_id` = ? WHERE `id` = ?");

            $stmt->execute([$product->price, $product->name, $product->image, $product->description, $product->category_id, $id]);

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