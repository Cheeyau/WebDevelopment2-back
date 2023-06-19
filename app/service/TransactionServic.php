<?php

namespace Services;

use Models\Transaction;
Use Repositories\TransactionRepository;
Use Repositories\UserRepository;

class TransactionService {
    
    private $repo;
    private $user_repo;
    function __construct() {
        $this->repo  = new TransactionRepository();
        $this->user_repo = new UserRepository();
    }

    public function getAll($offset = NULL, $limit = NULL): mixed {
        return $this->repo->getAll($offset, $limit);
    }

    public function getById(int $id): mixed {
        return $this->repo->getById($id);
    }

    // TODO check user id 
    public function create(Transaction $transaction): mixed {       
        return $this->repo->create($transaction);        
    }

    public function updateStatus(Transaction $transaction, int $id): mixed {       
        return $this->repo->updateStatus($transaction, $id);        
    }
}