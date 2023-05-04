<?php

namespace Controller;

use Service\OrderService;
use Exception;

class OrderController extends Controller {

    function __construct(
        private $service = new OrderService()
    ) {}

    public function getAll() : mixed {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        $products = $this->service->getAll($this->paginator()[0], $this->paginator()[1]);

        $this->respond($products);
    }

    public function getById($id) {
        if (!$this->checkJWTToken()) $this->respondWithError(500, "Invalid JWT Token");

        $product = $this->service->getById($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$product) {
            $this->respondWithError(404, "Product not found");
        }

        $this->respond($product);
    }

    public function create() :mixed {
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
}