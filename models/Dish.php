<?php

namespace models;

class Dish {
    private int $id;
    private string $name;
    private string $type;
    private array $ingredients;

    # Constructor
    public function __construct(
        int $id,
        string $name,
        string $type,
        array $ingredients,
        array $options
    ){
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->ingredients = $ingredients;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getName() : string {return $this->name;}
    public function getType() : string {return $this->type;}
    public function getIngredients() : array {return $this->ingredients;}

    # Setters
    public function setName(string $name) : void {$this->name = $name;}
    public function setType(string $type) : void {$this->type = $type;}
    public function setIngredients(array $ingredients) : void {$this->ingredients = $ingredients;}
}