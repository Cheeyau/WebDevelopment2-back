<?php

namespace Services;

use Repositories\OrderRepository;
use Models\Order;
use Models\Paginator;
use Exception;

class OrderService {
    
    private $user_repo;
    private $repo;
    function __construct() {
        $this->repo = new OrderRepository();
    }

    public function getAll(Paginator $paginator, $user_id) {
        try {
            return $this->repo->getAll($paginator, $user_id);
        } catch(Exception $e) {
            echo $e;
        }
    }

    public function getById(int $id) {
        return $this->repo->getById($id);
    }

    public function create(Order $order) {       
        return $this->repo->create($order);        
    }

    public function update(Order $order, int $id) {       
        return $this->repo->update($order, $id);        
    }
}