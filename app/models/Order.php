<?php

namespace Models;

use DateTime;
use Models\OrderDetail;
use Exception;

class Order {
    public int $id;
    public int $user_id;
    public string $name;
    public string $email_address;
    public string $created;
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

    public function __construct(int $id, int $user_id, string $name, string $email_address, string $created, array $items) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->name = $name;
        $this->email_address = $email_address;
        $this->created = $created;
        $this->items = $items;
    }
}