<?php

class OrderItem {
    private int $id;
    private string $name;
    private string $image;
    private array $options;
    private int $id_order;
    private int $id_menu;

    # Constructor
    public function __construct(
        int $id,
        string $name,
        string $image,
        array $options,
        int $id_order,
        int $id_menu
    ){
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->options = $options;
        $this->id_order = $id_order;
        $this->id_menu = $id_menu;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getName() : string {return $this->name;}
    public function getImage() : string {return $this->image;}
    public function getOptions() : array {return $this->options;}
    public function getIdOrder() : int {return $this->id_order;}
    public function getIdMenu() : int {return $this->id_menu;}

    # Setters
    public function setName(string $name) : void {$this->name = $name;}
    public function setImage(string $image) : void {$this->image = $image;}
    public function setOptions(array $options) : void {$this->options = $options;}
    public function setIdOrder(int $id_order) : void {$this->id_order = $id_order;}
    public function setIdMenu(int $id_menu) : void {$this->id_menu = $id_menu;}
}