<?php

/**
 * Project: INF653 Midterm
 * Filename: category.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: Defines a model object which handles the CRUD Logic
 *              for the category table.
 */

class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // GET All Categories
    public function read() {
        $query = 'SELECT id, category FROM ' . $this->table . ' ORDER BY id ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // GET Single Category
    public function read_single() {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->category = $row['category'];
            return true;
        }
        return false;
    }

    // CREATE Category
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category) RETURNING id';
        $stmt = $this->conn->prepare($query);
        
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        }
        return false;
    }

    // UPDATE Category
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // DELETE Category
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}