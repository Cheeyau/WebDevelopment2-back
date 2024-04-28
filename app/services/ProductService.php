<?php

namespace Services;

use Models\Paginator;
use Repositories\ProductRepository;
use Models\Product;

class ProductService {

    private $repo;
    function __construct() {
        $this->repo = new ProductRepository();
    }

    public function getAll(Paginator $pages) {
        return $this->repo->getAll($pages);
    }
    public function getCategories() {
        return $this->repo->getCategories();
    }

    public function getById(int $id) {
        return $this->repo->getById($id);
    }

    public function create(Product $product) {       
        return $this->repo->create($product);        
    }

    public function update(Product $product, int $id) {       
        return $this->repo->update($product, $id);        
    }

    public function delete(int $id) {       
        return $this->repo->delete($id);        
    }
}