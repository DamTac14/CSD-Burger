<?php

namespace models;

class Menu {
    private int $id;
    private string $name;
    private array $includesDishes;

    # Constructor
    public function __construct(
        int $id,
        string $name,
        array $includesDishes
    ){
        $this->id = $id;
        $this->name = $name;
        $this->includesDishes = $includesDishes;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getName() : string {return $this->name;}
    public function getIncludesDishes() : array {return $this->includesDishes;}

    # Setters
    public function setName(string $name) : void {$this->name = $name;}
    public function setIncludesDishes(array $includesDishes) : void {$this->includesDishes = $includesDishes;}

    # Methods
    public function addDish(Dish $new_dish) : void {$this->includesDishes[] = $new_dish;}
    public function removeDish(Dish $dish_to_remove) : void {
        $index = array_search($dish_to_remove, $this->includesDishes);
        unset($this->includesDishes[$index]);
    }
}