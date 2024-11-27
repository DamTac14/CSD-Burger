<?php

class Stock {
    public int $id;
    public int $quantity;
    public int $threshold;

    # Constructor
    public function __construct(int $id, int $quantity, int $threshold){
        $this->id = $id;
        $this->quantity = $quantity;
        $this->threshold = $threshold;
    }

    # Getters
    public function getId(): int {return $this->id;}
    public function getQuantity(): int {return $this->quantity;}
    public function getThreshold(): int {return $this->threshold;}

    # Setters
    public function setQuantity(int $quantity): void {$this->quantity = $quantity;}
    public function setThreshold(int $threshold): void {$this->threshold = $threshold;}
}