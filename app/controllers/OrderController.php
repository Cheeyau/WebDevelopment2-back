<?php

namespace Controllers;

use Services\OrderService;
use Exception;

class OrderController extends Controller {

    private $service;

    function __construct(
        ) {
            $this->service = new OrderService();

    }

    public function getAll() {
        $user = $this->checkJWTToken();

        $paginator[] = $this->paginator();
        $products = $this->service->getAll($paginator[0], $paginator[1], $user->id);

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