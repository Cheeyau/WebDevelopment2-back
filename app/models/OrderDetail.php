<?php

namespace Models;

class OrderDetail {
    public int $id;
    public int $product_id;
    public string $name;
    public int $amount;
    public float $price;
    
    function __construct(int $product_id, string $name, int $amount, $price) {
        $this->product_id = $product_id;
        $this->name = $name;
        $this->amount = $amount;
        $this->price = $price;
    }
}