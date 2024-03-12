<?php

namespace Models;

use DateTime;

class Transaction {
    
    public int $id;
    public float $amount;
    public int $userId;
    public string $name;
    public DateTime $created;
    public int $orderId;
    public string $status;

}