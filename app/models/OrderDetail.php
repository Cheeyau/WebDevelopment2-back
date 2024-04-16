<?php

namespace Models;

class OrderDetail {
    public int $id;
    public string $product_id;
    public string $name;
    public int $amount;
    public float $price;
    
    function __construct(int $id, string $product_id, string $name, int $amount, $price) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->name = $name;
        $this->amount = $amount;
        $this->price = $price;
    }
}