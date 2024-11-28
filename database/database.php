<?php
function getDB() {
    static $db = null; 
    if ($db === null) {
        try {
            $db = new PDO('mysql:host=localhost;dbname=csdburgers;charset=utf8mb4', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
    return $db;
}
