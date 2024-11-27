<?php

class OrderItem {
    private int $id;
    private string $name;
    private string $image;
    private array $options;
    private int $order;
    private int $menu;

    # Constructor
    public function __construct(
        int $id,
        string $name,
        string $image,
        array $options,
        int $order,
        int $menu
    ){
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->options = $options;
        $this->order = $order;
        $this->menu = $menu;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getName() : string {return $this->name;}
    public function getImage() : string {return $this->image;}
    public function getOptions() : array {return $this->options;}
    public function getIdOrder() : int {return $this->order;}
    public function getIdMenu() : int {return $this->menu;}

    # Setters
    public function setName(string $name) : void {$this->name = $name;}
    public function setImage(string $image) : void {$this->image = $image;}
    public function setOptions(array $options) : void {$this->options = $options;}
    public function setIdOrder(int $order) : void {$this->order = $order;}
    public function setIdMenu(int $menu) : void {$this->menu = $menu;}
}