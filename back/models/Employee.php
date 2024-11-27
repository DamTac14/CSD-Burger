<?php

class Employee {
    private int $id;
    private string $role;
    private string $login;
    private string $password;
    private bool $is_active;
    private DateTime $hire_date;
    private DateTime $departure_date;

    # Constructor
    public function __construct(
        int $id,
        string $role,
        string $login,
        string $password,
        bool $is_active,
        DateTime $hire_date,
        DateTime $departure_date
    ){
        $this->id = $id;
        $this->role = $role;
        $this->login = $login;
        $this->password = $password;
        $this->is_active = $is_active;
        $this->hire_date = $hire_date;
        $this->departure_date = $departure_date;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getRole() : string {return $this->role;}
    public function getLogin() : string {return $this->login;}
    public function getIsActive() : bool {return $this->is_active;}
    public function getHireDate() : DateTime {return $this->hire_date;}
    public function getDepartureDate() : DateTime {return $this->departure_date;}

    # Setters
    public function setRole(string $role) : void {$this->role = $role;}
    public function setLogin(string $login) : void {$this->login = $login;}
    public function setPassword(string $password) : void {$this->password = $password;}
    public function setIsActive(bool $is_active) : void {$this->is_active = $is_active;}
    public function setHireDate(DateTime $hire_date) : void {$this->hire_date = $hire_date;}
    public function setDepartureDate(DateTime $departure_date) : void {$this->departure_date = $departure_date;}
}