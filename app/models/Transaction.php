<?php

namespace Models;

use DateTime;

class Transaction {
    
    public int $id;
    public float $total;
    public int $user_id;
    public string $created;
    public int $order_id;
    public string $status;

    public static function create(int $user_id, float $total, string $status) {
        $instance = new self(); 
        $instance->total = $total;
        $instance->user_id = $user_id;
        $instance->status = $status;
        return $instance;
    }
}