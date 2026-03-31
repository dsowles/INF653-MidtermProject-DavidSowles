<?php
/**
 * Project: INF653 Midterm
 * Filename: database.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: Defines a class object which is responsible for establishing a connection
 *              to the postgreSQL database.
 */



class Database {
    private $conn;

    public function connect() {
        // Environment variable for Render.
        $url = getenv('DATABASE_URL');

        if ($url) {
            // Parse the connection string for Render
            $dbparts = parse_url($url);
            $host = $dbparts['host'];
            $port = $dbparts['port'];
            $user = $dbparts['user'];
            $pass = $dbparts['pass'];
            $dbname = ltrim($dbparts['path'], '/');
        } else {
            // Local defaults
            $host = 'localhost';
            $port = '5432';
            $dbname = 'midtermdb';
            $user = 'postgres';
            $pass = 'pass';
        }

        $this->conn = null;

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // On production, don't echo the full error for security
            error_log('Connection Error: ' . $e->getMessage());
        }

        return $this->conn;
    }
}