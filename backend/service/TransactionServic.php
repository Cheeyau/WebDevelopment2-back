<?php

namespace Service;

use Model\Transaction;
Use Repository\TransactionRepository;
Use Repository\UserRepository;

class TransactionService {
    
    function __construct(
        private $repo = new TransactionRepository(),
        private $user_repo = new UserRepository()
    ) {}

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
        return $this->repo->update($transaction, $id);        
    }
}