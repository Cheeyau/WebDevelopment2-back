<?php

namespace Services;

use Repositories\OrderRepository;
use Services\TransactionService;
use Models\Order;
use Models\Paginator;
use Models\Transaction;
use Exception;

class OrderService {
    
    private $transaction_service;
    private $repo;
    function __construct() {
        $this->repo = new OrderRepository();
        $this->transaction_service = new TransactionService();
    }

    public function getAll(Paginator $paginator, int $user_id, int $user_role) {
        try {
            return $this->repo->getAll($paginator, $user_id, $user_role);
        } catch(Exception $e) {
            echo $e;
        }
    }

    public function getById(int $id) {
        return $this->repo->getById($id);
    }

    public function create(Order $order, Transaction $transaction)  {
        $saved_transaction = $this->transaction_service->create($transaction);
        $order->transaction_id = $saved_transaction->id;
        return $this->repo->create($order);        
    }

    public function updateStatus(Order $order, int $id) {
        return $this->repo->updateStatus($order, $id);        
    }
}