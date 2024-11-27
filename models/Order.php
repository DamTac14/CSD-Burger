<?php

namespace models;

use DateTime;
use OrderItem;

class Order {
    public int $id;
    public int $number;
    public array $items;
    public string $status;
    public DateTime $orderDate;
    public bool $takeaway;

    # Constructor
    public function __construct(
        int $id,
        int $number,
        array $items,
        string $status,
        DateTime $orderDate,
        bool $takeaway
    ){
        $this->id = $id;
        $this->number = $number;
        $this->items = $items;
        $this->status = $status;
        $this->orderDate = $orderDate;
        $this->takeaway = $takeaway;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getNumber() : int {return $this->number;}
    public function getItems() : array {return $this->items;}
    public function getStatus() : string {return $this->status;}
    public function getOrderDate() : DateTime {return $this->orderDate;}
    public function getIsTakeaway() : bool {return $this->takeaway;}

    # Setters
    public function setItems(array $items) : void {$this->items = $items;}
    public function setStatus(string $status) : void {$this->status = $status;}

    # Methods
    public function getTicket() : string {return "";}
    public function getTotalPrice() : float {return 0;}
    public function addItem(OrderItem $item) : void {}
}