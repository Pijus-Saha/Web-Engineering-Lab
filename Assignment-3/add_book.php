<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // If JSON data is not available, try form data
    if (!$data) {
        $data = $_POST;
    }
    
    $title = isset($data['title']) ? trim($data['title']) : '';
    $author = isset($data['author']) ? trim($data['author']) : '';
    $genre = isset($data['genre']) ? trim($data['genre']) : '';
    $best_selling = isset($data['best_selling']) ? (bool)$data['best_selling'] : false;
    
    // Validate input
    if (empty($title) || empty($author) || empty($genre)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO books (title, author, genre, best_selling) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $author, $genre, $best_selling);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Book added successfully', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding book: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
}

$conn->close();
?>

