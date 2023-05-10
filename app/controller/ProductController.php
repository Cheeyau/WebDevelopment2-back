<?php

namespace Controllers;

use Exception;

use Service\ProductService;

class ProductController extends Controller {

    private $service;
    function __construct() {
        $this->service = new ProductService();

    }

    public function getAll(): mixed {
        $products = $this->service->getAll($this->paginator()[0], $this->paginator()[1]);

        $this->respond($products);
    }

    public function getById($id): mixed {
        $product = $this->service->getById($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$product) {
            $this->respondWithError(404, "Product not found");
        }

        $this->respond($product);
    }

    public function create(): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $product = $this->createObjectFromPostedJson("Model\\Product");
            $product = $this->service->create($product);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }

    public function update($id): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $product = $this->createObjectFromPostedJson("Model\\Product");
            $product = $this->service->update($product, $id);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }

    public function delete($id): mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }
}