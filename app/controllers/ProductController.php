<?php

namespace Controllers;

use Exception;

use Services\ProductService;
use Services\UserService;

class ProductController extends Controller {

    private $service;
    function __construct() {
        $this->service = new ProductService();
        $user_service = new UserService();
        $this->setUserService($user_service);
    }
    public function getAllCategory() {
        $this->respond($this->service->getCategories());
    }
    public function getAll() {
        $this->respond($this->service->getAll($this->paginator()));
    }

    public function getById($id) {
        $product = $this->service->getById($id);

        if (!$product) {
            $this->respondWithError(401, "Product not found");
        }

        $this->respond($product);
    }

    public function create() {
        if ($token = $this->checkJWTToken()) {
            try {
                $product = $this->createObjectFromPostedJson("Models\\Product");
                
                if ($this->checkLoginUser($token->user->id, $token->user->id) || $this->getUserRoll($token->user->id) === 0) {
                    $this->respondWithError(401, $this->user_unauthorized);
                } else {
                    $product = $this->service->create($product);
                    $this->respond($product);
                }
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }

    public function update($id) {
        if ($token =    $this->checkJWTToken()) {
            try {
                $product = $this->createObjectFromPostedJson("Models\\Product");
                if ($this->checkLoginUser($token->user->id, $token->user->id) || $this->getUserRoll($token->user->id) === 0) {
                    $this->respondWithError(401, $this->user_unauthorized);
                } else {
                    $product = $this->service->update($product, $id);
                    $this->respond($product);
                }
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }

    public function delete($id) {
        if ($token = $this->checkJWTToken()) {
            try {
                if ($this->checkLoginUser($token->user->id, $token->user->id) || $this->getUserRoll($token->user->id) === 0) {
                    $this->respondWithError(401, $this->user_unauthorized);
                } else {
                    $this->service->delete($id);
                    $this->respond(true);
                }
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }
}