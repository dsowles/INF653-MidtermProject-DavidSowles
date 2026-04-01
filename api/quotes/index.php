<?php
/**
 * Project: INF653 Midterm
 * Filename: index.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: This php script implements the routing logic
 *              for the quotes data.
 */


// HTTP Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

// Imports
//include_once '../../config/Database.php';
//include_once '../../models/Quote.php';
//include_once '../../models/Author.php';
//include_once '../../models/Category.php';

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Author.php'; 
include_once __DIR__ . '../../models/Author.php';
include_once __DIR__ . '../../models/Category.php';


// Initial Object Setup
$database = new Database();
$db = $database->connect();

$quoteObj = new Quote($db);
$authorObj = new Author($db);
$catObj = new Category($db);

// Get the raw data for POST/PUT/DELETE
// Return error message if required field is missing
$data = json_decode(file_get_contents("php://input"));

// Main Routing Logic:
switch($method) {
    case 'GET':
        // see https://www.php.net/manual/en/reserved.variables.get.php for docs on _GET.
        if (isset($_GET['id'])) {
            $quoteObj->id = $_GET['id'];
            if($quoteObj->read_single()) {
                echo json_encode([
                    'id' => $quoteObj->id,
                    'quote' => $quoteObj->quote,
                    'author' => $quoteObj->author_name,
                    'category' => $quoteObj->category_name
                ]);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        } else {
            // Check for filters
            $auth_id = $_GET['author_id'] ?? null;
            $cat_id = $_GET['category_id'] ?? null;

            $result = $quoteObj->read($auth_id, $cat_id);
            $num = $result->rowCount();

            if($num > 0) {
                echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        }
        break;

    case 'POST':
        // With POST, our query data is in the body (JSON).
        if(!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        // Validate Author and Category IDs exist
        $authorObj->id = $data->author_id;
        $catObj->id = $data->category_id;
        
        if(!$authorObj->read_single()) {
            echo json_encode(['message' => 'author_id Not Found']);
        } elseif (!$catObj->read_single()) {
            echo json_encode(['message' => 'category_id Not Found']);
        } else {
            $quoteObj->quote = $data->quote;
            $quoteObj->author_id = $data->author_id;
            $quoteObj->category_id = $data->category_id;
            if($quoteObj->create()) {
                echo json_encode([
                    'id' => $quoteObj->id,
                    'quote' => $quoteObj->quote,
                    'author_id' => $quoteObj->author_id,
                    'category_id' => $quoteObj->category_id
                ]);
            }
        }
        break;

    case 'PUT':
        if(!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $quoteObj->id = $data->id;
        $authorObj->id = $data->author_id;
        $catObj->id = $data->category_id;

        if(!$authorObj->read_single()) {
            echo json_encode(['message' => 'author_id Not Found']);
        } elseif (!$catObj->read_single()) {
            echo json_encode(['message' => 'category_id Not Found']);
        } else {
            $quoteObj->quote = $data->quote;
            $quoteObj->author_id = $data->author_id;
            $quoteObj->category_id = $data->category_id;
            
            if($quoteObj->update()) {
                echo json_encode([
                    'id' => $quoteObj->id,
                    'quote' => $quoteObj->quote,
                    'author_id' => $quoteObj->author_id,
                    'category_id' => $quoteObj->category_id
                ]);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        }
        break;

    case 'DELETE':
        if(!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }
        $quoteObj->id = $data->id;
        if($quoteObj->delete()) {
            echo json_encode(['id' => $quoteObj->id]);
        } else {
            echo json_encode(['message' => 'No Quotes Found']);
        }
        break;
}