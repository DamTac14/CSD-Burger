<?php

class Order {
    public int $id;
    public int $number;
    public array $items; //OrderItems
    public string $status;
    public DateTime $order_date;
    public bool $takeaway;

    # Constructor
    public function __construct(
        int $id,
        int $number,
        array $items,
        string $status,
        DateTime $order_date,
        bool $takeaway
    ){
        $this->id = $id;
        $this->number = $number;
        $this->items = $items;
        $this->status = $status;
        $this->order_date = $order_date;
        $this->takeaway = $takeaway;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getNumber() : int {return $this->number;}
    public function getItems() : array {return $this->items;}
    public function getStatus() : string {return $this->status;}
    public function getOrderDate() : DateTime {return $this->order_date;}
    public function getTakeaway() : bool {return $this->takeaway;}

    # Setters
    public function setItems(array $items) : void {$this->items = $items;}
    public function setStatus(string $status) : void {$this->status = $status;}
}