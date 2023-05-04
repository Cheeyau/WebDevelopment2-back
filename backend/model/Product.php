<?php

namespace Model;

class Product {

    function __construct(
        public int $id,
        public float $price,
        public string $name,
        public string $image_path,
        public string $description
    ) {}
}