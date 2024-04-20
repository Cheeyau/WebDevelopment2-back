<?php

namespace Services;

use Models\Paginator;
use Models\Transaction;
Use Repositories\TransactionRepository;

class TransactionService {
    
    private $repo;
    function __construct() {
        $this->repo  = new TransactionRepository();
    }

    public function getAll(Paginator $paginator, int $user) {
        return $this->repo->getAll($paginator, $user);
    }

    public function getById(int $id, int $user_id) {
        return $this->repo->getById($id, $user_id);
    }

    public function create(Transaction $transaction) {       
        return $this->repo->create($transaction);        
    }

    public function updateStatus(Transaction $transaction, int $id) {       
        return $this->repo->updateStatus($transaction, $id);        
    }
}