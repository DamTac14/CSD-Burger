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
}