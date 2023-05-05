<?php

namespace Controllers;

use Service\TransactionService;
use Exception;

class TransactionController extends Controller {

    function __construct(
        private $service = new TransactionService()  
    ) {}

    public function getAll(): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        $transactions = $this->service->getAll($this->paginator()[0], $this->paginator()[1]);

        $this->respond($transactions);
    }

    public function getById($id): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        $transaction = $this->service->getById($id);

        if (!$transaction) {
            $this->respondWithError(404, "transaction not found");
        }

        $this->respond($transaction);
    }

    public function create(): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $transaction = $this->createObjectFromPostedJson("Models\\Transaction");
            $transaction = $this->service->create($transaction);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($transaction);
    }

    public function updateStatus($id): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $transaction = $this->createObjectFromPostedJson("Model\\Transaction");
            $transaction = $this->service->updateStatus($transaction, $id);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($transaction);
    }
}