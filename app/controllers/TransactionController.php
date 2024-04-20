<?php

namespace Controllers;

use Services\TransactionService;
use Services\UserService;
use Exception;
class TransactionController extends Controller {

    private $service;
    private $user_service;

    function __construct() {
        $this->service = new TransactionService();
        $this->user_service = new UserService();
        $this->setUserService($this->user_service);
    }

    public function getAll() {
        if ($token = $this->checkJWTToken()) {

            $paginator = $this->paginator();

            $user_roll = (isset($paginator->id)) ? $this->getUserRoll($paginator->id) : $this->getUserRoll($token->user->id);

            $transactions = $this->service->getAll($this->paginator(), $token->user->id, $user_roll);
            
            if (!isset($transactions)) {
                $this->respond($transactions);
            } else {
                ($this->checkLoginUser($token->user->id, $transactions[0]->user_id)) 
                    ? $this->respondWithError(401, $this->user_unauthorized) : $this->respond($transactions);
            }
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }

    }

    public function getById($id) {
        if ($token = $this->checkJWTToken()) {
            $transaction = $this->service->getById($id, $token->user->id);
                        
            if (!$transaction) {
                $this->respond($transaction);
            } else {
                ($this->checkLoginUser($token->user->id, $transaction->user_id)) 
                    ? $this->respondWithError(401, $this->user_unauthorized) : $this->respond($transaction);
            }
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }

    }

    public function create() {
        if ($token =$this->checkJWTToken()) {
            try {
                $transaction = $this->createObjectFromPostedJson("Models\\Transaction");

                if($this->checkLoginUser($token->user->id, $transaction->user_id)) {
                    $this->respondWithError(401, $this->user_unauthorized);
                } else {
                    $this->service->create($transaction);
                    $this->respond($transaction);
                }
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }

    }

    public function updateStatus($id) {
        if ($token = $this->checkJWTToken()) {
            try {
                $transaction = $this->createObjectFromPostedJson("Models\\Transaction");
                
                if ($this->checkLoginUser($token->user->id, $transaction->user_id)) {
                    $this->respondWithError(401, $this->user_unauthorized);
                } else {
                    $transaction = $this->service->updateStatus($transaction, $id);
                    $this->respond($transaction);
                }
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
        } else {
            $this->respondWithError(404, $this->jwt_not_found);
        }
    }
}