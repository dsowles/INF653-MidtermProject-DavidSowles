<?php
/**
 * Project: INF653 Midterm
 * Filename: index.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: This php script implements the routing logic
 *              for the categories data.
 */


// HTTP Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

// CORS stuff.
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// Imports
//include_once '../../config/Database.php';
//include_once '../../models/Author.php';

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/author.php'; 

// Initial Object Setup
$database = new Database();
$db = $database->connect();
$categoryObj = new Category($db);

// Get the raw data for POST/PUT/DELETE
$data = json_decode(file_get_contents("php://input"));

// Main Routing Logic:
switch($method) {
    case 'GET':
        // see https://www.php.net/manual/en/reserved.variables.get.php for docs on _GET.
        if (isset($_GET['id'])) {
            $categoryObj->id = $_GET['id'];
            if($categoryObj->read_single()) {
                echo json_encode(['id' => $categoryObj->id, 'category' => $categoryObj->category]);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        } else {
            $result = $categoryObj->read();
            $num = $result->rowCount();
            if($num > 0) {
                $cat_arr = $result->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($cat_arr);
            } else {
                echo json_encode(['message' => 'No Categories Found']);
            }
        }
        break;

    case 'POST':
        // With POST, our query data is in the body (JSON).
        // Return error message if required field is missing
        if(!isset($data->category)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }
        $categoryObj->category = $data->category;
        if($categoryObj->create()) {
            echo json_encode(['id' => $categoryObj->id, 'category' => $categoryObj->category]);
        }
        break;

    case 'PUT':
        if(!isset($data->id) || !isset($data->category)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }
        $categoryObj->id = $data->id;
        $categoryObj->category = $data->category;
        if($categoryObj->update()) {
            echo json_encode(['id' => $categoryObj->id, 'category' => $categoryObj->category]);
        } else {
            echo json_encode(['message' => 'category_id Not Found']);
        }
        break;

    case 'DELETE':
        if(!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }
        $categoryObj->id = $data->id;
        if($categoryObj->delete()) {
            echo json_encode(['id' => $categoryObj->id]);
        } else {
            echo json_encode(['message' => 'category_id Not Found']);
        }
        break;
}