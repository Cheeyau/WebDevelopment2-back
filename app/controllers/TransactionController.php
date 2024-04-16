<?php

namespace Controllers;

use Services\TransactionService;
use Exception;

class TransactionController extends Controller {

    private $service;

    function __construct() {
        $this->service = new TransactionService();  
    }

    public function getAll() {
        if ($this->checkJWTToken()) {
            $transactions = $this->service->getAll($this->paginator());
    
            $this->respond($transactions);
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }

    }

    public function getById($id) {
        if ($this->checkJWTToken()) {
            $transaction = $this->service->getById($id);
    
            if (!$transaction) {
                $this->respondWithError(404, "transaction not found");
            }
    
            $this->respond($transaction);
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }

    }

    public function create() {
        if ($this->checkJWTToken()) {
            try {
                $transaction = $this->createObjectFromPostedJson("Models\\Transaction");
                $transaction = $this->service->create($transaction);
    
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
            $this->respond($transaction);
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }

    }

    public function updateStatus($id) {
        if ($this->checkJWTToken()) {
            try {
                $transaction = $this->createObjectFromPostedJson("Models\\Transaction");
                $transaction = $this->service->updateStatus($transaction, $id);
    
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
            $this->respond($transaction);
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }
    }
}