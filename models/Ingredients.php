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
    public function getStock() : int {return $this->stock;}

    # Setters
    public function setName(string $name) : void {$this->name = $name;}
    public function setAllergens(array $allergens) : void {$this->allergens = $allergens;}
    public function setPrice(float $price) : void {$this->price = $price;}

    # Methods
    public function restock(int $amount) : void {$this->stock += $amount;}
    public function destock(int $amount) : void {$this->stock -= $amount;}
    public function thresholdReached() : bool {return $this->stock < 10;}
    public function addAllergen(string $allergen) : void {$this->allergens[] = $allergen;}
    public function removeAllergen(string $allergen) : void {
        $index = array_search($allergen, $this->allergens);
        unset($this->allergens[$index]);
    }
}