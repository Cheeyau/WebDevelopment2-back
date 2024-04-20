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
    public string $transaction_status;
    public string $status;

    public static function create(int $id, int $user_id, string $name, string $email_address, string $created, array $items, Float $total, string $transaction_status, string $status) {
        $instance = new self();
        $instance->id = $id;
        $instance->user_id = $user_id;
        $instance->name = $name;
        $instance->email_address = $email_address;
        $instance->created = $created;
        $instance->items = $items;
        $instance->total = $total;
        $instance->transaction_status = $transaction_status;
        $instance->status = $status;
        return $instance;
    }
}