<?php

namespace Controllers;

use Services\OrderService;
use Services\UserService;
Use Models\OrderDetail;
Use Models\Order;
Use Models\Transaction;
use Exception;

class OrderController extends Controller {

    private $service;
    private $user_service;

    function __construct() {
        $this->service = new OrderService();
        $this->user_service = new UserService();
    }
    private function convertOrderDetail(Order $order) {
        $order_detail = [];
        foreach ($order->items as $item) {
            $order_detail[] = OrderDetail::create($item->product_id, $item->amount);
        }
        $order->items = $order_detail;
        return $order;        
    }

    private function checkLoginUser(int $user_id, int $order_user_id) {
        $login_user = $this->user_service->getById($user_id);
        return (!$login_user || (($login_user->id === $order_user_id) && ($login_user->user_roll === 0)) || $login_user->user_roll > 0) ? false : true;
    }

    public function getAll() {
        if ($token = $this->checkJWTToken()) {
            $orders = $this->service->getAll($this->paginator(), $token->user->id);
            
            if (!$orders) {
                $this->respond($orders);
            } else {
                ($this->checkLoginUser($token->user->id, $orders[0]->user_id)) 
                    ? $this->respondWithError(401, $this->user_unauthorized) : $this->respond($orders);
            }
            
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }

    public function getById($id) {
        if ($token = $this->checkJWTToken()) {
            $order = $this->service->getById($id);
            
            if (!$order) {
                $this->respond($order);
            } else {
                ($this->checkLoginUser($token->user->id, $order[0]->user_id)) 
                    ? $this->respondWithError(401, $this->user_unauthorized) : $this->respond($order);;
            }

        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }

    public function create() {
        if ($token = $this->checkJWTToken()) {
            try {
                $order = $this->createObjectFromPostedJson("Models\\Order");
                if($this->checkLoginUser($token->user->id, $order->user_id)) {
                    $this->respondWithError(401, $this->user_unauthorized);
                } else {
                    $transaction = Transaction::create($order->user_id, $order->total, $order->status);
                    $this->service->create($token->user->id, $this->convertOrderDetail($order), $transaction);
                    $this->respond($order);
                }
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
            
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }

    public function update($id) {
        if ($token = $this->checkJWTToken()) {
            try {
                $order = $this->createObjectFromPostedJson("Models\\Order");
                
                if ($this->checkLoginUser($token->user->id, $order->user_id)) {
                    $this->respondWithError(401, $this->user_unauthorized);
                } else {
                    $order = $this->service->update($this->convertOrderDetail($order), $id);
                    $this->respond($order);
                }

            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }
}