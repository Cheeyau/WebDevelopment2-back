<?php

namespace Controllers;

use Exception;

use Services\ProductService;

class ProductController extends Controller {

    private $service;
    function __construct() {
        $this->service = new ProductService();

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
        if ($this->checkJWTToken()) {
            try {
                $product = $this->createObjectFromPostedJson("Models\\Product");
                $this->service->create($product);
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
                $product = $this->createObjectFromPostedJson("Models\\Product");
                $product = $this->service->update($product, $id);
    
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
            $this->respond($product);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }
    }

    public function delete($id) {
        if ($this->checkJWTToken()) {
            try {
                $this->service->delete($id);
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
    
            $this->respond(true);
        } else {
            $this->respondWithError(401, $this->jwt_not_found);
        }

    }
}