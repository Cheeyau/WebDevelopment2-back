<?php

namespace Service;

use Repository\OrderRepository;
use Repository\UserRepository;
use Model\Order;
use Exception;

class OrderService {
    
    private $user_repo;
    private $repo;
    function __construct() {
        $this->user_repo = new UserRepository();
        $this->repo = new OrderRepository();
    }

    public function getAll($offset = NULL, $limit = NULL, $user_id): mixed {
        try {
            return $this->repo->getAll($offset, $limit);
        } catch(Exception $e) {
            echo $e;
        }
    }

    public function getById(int $id): mixed {
        return $this->repo->getById($id);
    }

    public function create(Order $order): mixed {       
        return $this->repo->create($order);        
    }

    public function update(Order $order, int $id): mixed {       
        return $this->repo->update($order, $id);        
    }
}