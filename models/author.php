<?php
/**
 * Project: INF653 Midterm
 * Filename: author.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: Defines a model object which handles the CRUD Logic
 *              for the author table.
 */


class Author {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    // GET All Authors
    public function read() {
        $query = 'SELECT id, author FROM ' . $this->table . ' ORDER BY id ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // GET Single Author
    public function read_single() {
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->author = $row['author'];
            return true;
        }
        return false;
    }

    // CREATE Author
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author) RETURNING id';
        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        }
        return false;
    }

    // UPDATE Author
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // DELETE Author
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