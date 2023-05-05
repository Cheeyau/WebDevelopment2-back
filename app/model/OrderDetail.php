<?php

namespace Model;

class OrderDetail {

    function __construct(
        public int $id,
        public string $product_id,
        public string $name,
        public int $amount,
        public float $price
    ) {}
}