<?php
/**
 * Project: INF653 Midterm
 * Filename: quote.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: Defines a model object which handles the CRUD Logic
 *              for the quote table.
 */


class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author_name;
    public $category_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // GET Quotes (Handles All, Author_id, Category_id, or Both)
    public function read($author_id = null, $category_id = null) {
        $query = 'SELECT q.id, q.quote, a.author as author_name, c.category as category_name 
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id';

        $conditions = [];
        $params = [];

        if ($author_id) {
            $conditions[] = 'q.author_id = ?';
            $params[] = $author_id;
        }
        if ($category_id) {
            $conditions[] = 'q.category_id = ?';
            $params[] = $category_id;
        }

        if (count($conditions) > 0) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query .= ' ORDER BY q.id ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    // GET Single Quote
    public function read_single() {
        $query = 'SELECT q.id, q.quote, a.author as author_name, c.category as category_name 
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.id = ? LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->quote = $row['quote'];
            $this->author_name = $row['author_name'];
            $this->category_name = $row['category_name'];
            return true;
        }
        return false;
    }

    // CREATE Quote
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id) RETURNING id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        }
        return false;
    }

    // UPDATE Quote
    public function update() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET quote = :quote, author_id = :author_id, category_id = :category_id 
                  WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // DELETE Quote
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}