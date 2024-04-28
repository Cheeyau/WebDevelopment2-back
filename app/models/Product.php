<?php

namespace Models;

class Product {

    public int $id;
    public float $price;
    public string $name;
    public string $image;
    public string $description;
    public Category $category;
    public int $category_id;
}