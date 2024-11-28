<?php

namespace models;

class Dish__Ingredient {
    private int $dish;
    private int $ingredient;
    private int $quantity;
    private bool $isAdditional;
    private string $status;

    # Constructor
    public function __construct(
        int $dish,
        int $ingredient,
        int $quantity,
        bool $isAdditional,
        string $status
    ){
        $this->dish = $dish;
        $this->ingredient = $ingredient;
        $this->quantity = $quantity;
        $this->isAdditional = $isAdditional;
        $this->status = $status;
    }

    # Getters
    public function getDish() : int {return $this->dish;}
    public function getIngredient() : int {return $this->ingredient;}
    public function getQuantity() : int {return $this->quantity;}
    public function getIsAdditional() : bool {return $this->isAdditional;}
    public function getStatus() : string {return $this->status;}

    # Setters
    public function setQuantity(int $quantity) : void {$this->quantity = $quantity;}
    public function setStatus(string $status) : void {$this->status = $status;}
}