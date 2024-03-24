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
        $products = $this->service->getAll($this->paginator());

        $this->respond($products);
    }

    public function getById($id) {
        $product = $this->service->getById($id);

        if (!$product) {
            $this->respondWithError(404, "Product not found");
        }

        $this->respond($product);
    }

    public function create() {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $product = $this->createObjectFromPostedJson("Models\\Product");
            $this->service->create($product);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }

    public function update($id) {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $product = $this->createObjectFromPostedJson("Models\\Product");
            $product = $this->service->update($product, $id);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }

    public function delete($id) {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }
}