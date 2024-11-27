<?php

namespace models;

use DateTime;

class Employee {
    private int $id;
    private string $role;
    private string $username;
    private string $password;
    private string $firstname;
    private string $lastname;
    private bool $isActive;
    private DateTime $hireDate;
    private DateTime $departureDate;

    # Constructor
    public function __construct(
        int $id,
        string $role,
        string $username,
        string $password,
        bool $isActive,
        DateTime $hireDate,
        DateTime $departureDate
    ){
        $this->id = $id;
        $this->role = $role;
        $this->username = $username;
        $this->password = $password;
        $this->isActive = $isActive;
        $this->hireDate = $hireDate;
        $this->departureDate = $departureDate;
    }

    # Getters
    public function getId() : int {return $this->id;}
    public function getRole() : string {return $this->role;}
    public function getUsername() : string {return $this->username;}
    public function getFirstname() : string {return $this->firstname;}
    public function getLastname() : string {return $this->lastname;}
    public function getFullName() : string {return $this->firstname." ".$this->lastname;}
    public function getIsActive() : bool {return $this->isActive;}
    public function getHireDate() : DateTime {return $this->hireDate;}
    public function getDepartureDate() : DateTime {return $this->departureDate;}

    # Setters
    public function setRole(string $role) : void {$this->role = $role;}
    public function setUsername(string $username) : void {$this->username = $username;}
    public function setFirstname(string $firstname) : void {$this->firstname = $firstname;}
    public function setLastname(string $lastname) : void {$this->lastname = $lastname;}
    public function setPassword(string $password) : void {$this->password = $password;}
    public function setIsActive(bool $isActive) : void {$this->isActive = $isActive;}
    public function setHireDate(DateTime $hireDate) : void {$this->hireDate = $hireDate;}
    public function setDepartureDate(DateTime $departureDate) : void {$this->departureDate = $departureDate;}
}