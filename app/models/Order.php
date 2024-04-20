<?php

namespace Models;

use DateTime;
use Models\OrderDetail;
use Exception;

class Order {
    public int $id;
    public int $user_id;
    public int $transaction_id;
    public string $name;
    public string $email_address;
    public string $created;
    public array $items;
    public float $total;
    public string $status;
    
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

    public static function create(int $id, int $user_id, string $name, string $email_address, string $created, array $items, Float $total, string $status) {
        $instance = new self();
        $instance->id = $id;
        $instance->user_id = $user_id;
        $instance->name = $name;
        $instance->email_address = $email_address;
        $instance->created = $created;
        $instance->items = $items;
        $instance->total = $total;
        $instance->status = $status;
        return $instance;
    }
}