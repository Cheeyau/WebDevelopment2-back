<?php

namespace Repositories;

use Models\Paginator;
use PDO;
use PDOException;

class Repository {
    protected $connection;    
    
    function __construct() {
        require __DIR__ . '/../config/dbconfig.php';
        try {
            $this->connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
                
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function setPaginator(\PDOStatement $stmt, Paginator $pages) { 
        if (isset($pages->category) && $pages->category > 0) $stmt->bindParam(':category_id', $pages->category, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $pages->limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $pages->offset, PDO::PARAM_INT);
        return $stmt;
    }
}