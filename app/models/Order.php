<?php

namespace Models;

use DateTime;
use Models\OrderDetail;
use Exception;

class Order {
    public int $id;
    public int $userId;
    public string $name;
    public string $emailAddress;
    public DateTime $created;
    public array $items;
    
    // public function getTotalPrice(): mixed {
    //     $total = 0;
    //     try {
    //         foreach($this->items as $item) {
    //             $total += ($item->amount * $item->price);
    //         }
    //         return $total;
    //     } catch(Exception $e) {
    //         return $e;
    //     }
    // }
}