<?php

namespace Models;

class OrderDetail {

    public int $id;
    public string $productId;
    public string $name;
    public int $amount;
    public float $price;
}