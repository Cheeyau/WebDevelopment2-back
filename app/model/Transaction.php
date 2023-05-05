<?php

namespace Model;

use DateTime;

class Transaction {
    
    function __construct(
        public int $id,
        public float $amount,
        public int $user_id,
        public string $name,
        public DateTime $created, 
        public int $order_id,
        public string $status
    ) {}
}