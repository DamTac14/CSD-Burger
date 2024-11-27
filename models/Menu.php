<?php

class Menu {
    private int $id;
    private string $name;
    private array $includes_dishes;

    # Constructor
    public function __construct(
        int $id,
        string $name,
        array $includes_dishes
    ){
        $this->id = $id;
        $this->name = $name;
        $this->includes_dishes = $includes_dishes;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getName() : string {return $this->name;}
    public function getIncludesDishes() : array {return $this->includes_dishes;}

    # Setters
    public function setName(string $name) : void {$this->name = $name;}
    public function setIncludesDishes(array $includes_dishes) : void {$this->includes_dishes = $includes_dishes;}
}