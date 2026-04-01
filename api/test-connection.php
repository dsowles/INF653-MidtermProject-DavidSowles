<?php
/**
 * Project: INF653 Midterm
 * Filename: test-connection.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: This is a short php script for testing the connection
 *              to the database.
 */


// Include the database and model
//include_once '../config/Database.php';
include_once __DIR__ . '../config/Database.php';
//include_once '../models/Author.php';
include_once __DIR__ . '../models/Author.php';

// Instantiate Database and Connect
$database = new Database();
$db = $database->connect();

if($db) {
    echo "Successfully connected to midtermdb!";
} else {
    echo "Connection failed.";
}