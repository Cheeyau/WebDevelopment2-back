<?php

namespace Controllers;

use Services\OrderService;
use Exception;

class OrderController extends Controller {

    private $service;

    function __construct() {
        $this->service = new OrderService();
    }

    public function getAll() {
        if ($token = $this->checkJWTToken()) {
            $products = $this->service->getAll($this->paginator(), $token->user->id);
    
            $this->respond($products);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }

    public function getById($id) {
        if ($this->checkJWTToken()) {
            $product = $this->service->getById($id);
    
            if (!$product) $this->respondWithError(404, "Product not found");
    
            $this->respond($product);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }

    public function create() {
        if ($this->checkJWTToken()) {
            try {
                $product = $this->createObjectFromPostedJson("Model\\Product");
                $product = $this->service->create($product);
    
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
            $this->respond($product);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }

    public function update($id) {
        if ($this->checkJWTToken()) {
            try {
                $product = $this->createObjectFromPostedJson("Model\\Product");
                $product = $this->service->update($product, $id);
    
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
            $this->respond($product);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }
}