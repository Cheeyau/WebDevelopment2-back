<?php

namespace Models;

class OrderDetail {

    public int $id;
    public string $product_id;
    public string $name;
    public int $amount;
    public float $price;
}