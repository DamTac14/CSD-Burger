<?php

namespace models;

use DateTime;

class Employee {
    private int $id;
    private string $role;
    private string $login;
    private string $password;
    private bool $isActive;
    private DateTime $hireDate;
    private DateTime $departureDate;

    # Constructor
    public function __construct(
        int $id,
        string $role,
        string $login,
        string $password,
        bool $isActive,
        DateTime $hireDate,
        DateTime $departureDate
    ){
        $this->id = $id;
        $this->role = $role;
        $this->login = $login;
        $this->password = $password;
        $this->isActive = $isActive;
        $this->hireDate = $hireDate;
        $this->departureDate = $departureDate;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getRole() : string {return $this->role;}
    public function getLogin() : string {return $this->login;}
    public function getIsActive() : bool {return $this->isActive;}
    public function getHireDate() : DateTime {return $this->hireDate;}
    public function getDepartureDate() : DateTime {return $this->departureDate;}

    # Setters
    public function setRole(string $role) : void {$this->role = $role;}
    public function setLogin(string $login) : void {$this->login = $login;}
    public function setPassword(string $password) : void {$this->password = $password;}
    public function setIsActive(bool $isActive) : void {$this->isActive = $isActive;}
    public function setHireDate(DateTime $hireDate) : void {$this->hireDate = $hireDate;}
    public function setDepartureDate(DateTime $departureDate) : void {$this->departureDate = $departureDate;}
}