<?php

namespace models;

class Ingredients {
    private int $id;
    private string $name;
    private array $allergens;
    private float $price;
    private int $stock;

    # Constructor
    public function __construct(
        int $id,
        string $name,
        array $allergens,
        float $price,
        int $stock
    ){
        $this->id = $id;
        $this->name = $name;
        $this->allergens = $allergens;
        $this->price = $price;
        $this->stock = $stock;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getName() : string {return $this->name;}
    public function getAllergens() : array {return $this->allergens;}
    public function getPrice() : float {return $this->price;}
    public function getIdStock() : int {return $this->stock;}

    # Setters
    public function setName(string $name) : void {$this->name = $name;}
    public function setAllergens(array $allergens) : void {$this->allergens = $allergens;}
    public function setPrice(float $price) : void {$this->price = $price;}
}