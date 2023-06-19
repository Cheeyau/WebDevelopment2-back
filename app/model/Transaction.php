<?php

namespace Models;

use DateTime;

class Transaction {
    
    public int $id;
    public float $amount;
    public int $user_id;
    public string $name;
    public DateTime $created;
    public int $order_id;
    public string $status;

}