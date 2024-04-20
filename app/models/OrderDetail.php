<?php

namespace Models;

class OrderDetail {
    public int $id;
    public int $order_id;
    public int $product_id;
    public string $name;
    public int $amount;
    public float $price;
    
    public static function get(int $id, int $product_id, string $name, int $amount, $price) {
        $instance = new self();
        $instance->id = $id;
        $instance->product_id = $product_id;
        $instance->name = $name;
        $instance->amount = $amount;
        $instance->price = $price;
        return $instance;
    }

    public static function create(int $product_id, int $amount) { 
        $instance = new self();
        $instance->product_id = $product_id;
        $instance->amount = $amount;
        return $instance;
    }

    public static function update(int $id, int $order_id, int $product_id, int $amount) { 
        $instance = new self();
        $instance->id = $id;
        $instance->product_id = $product_id;
        $instance->order_id = $order_id;
        $instance->amount = $amount;
        return $instance;
    }
}