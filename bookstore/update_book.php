<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // If JSON data is not available, try form data
    if (!$data) {
        $data = $_POST;
    }
    
    $book_id = isset($data['id']) ? (int)$data['id'] : 0;
    $title = isset($data['title']) ? trim($data['title']) : '';
    $author = isset($data['author']) ? trim($data['author']) : '';
    $genre = isset($data['genre']) ? trim($data['genre']) : '';
    $best_selling = isset($data['best_selling']) ? (bool)$data['best_selling'] : false;
    
    // Validate input
    if ($book_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Valid book ID is required']);
        exit;
    }
    
    if (empty($title) || empty($author) || empty($genre)) {
        echo json_encode(['success' => false, 'message' => 'Title, Author, and Genre are required']);
        exit;
    }
    
    // Check if book exists
    $check_stmt = $conn->prepare("SELECT id FROM books WHERE id = ?");
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Book not found']);
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();
    
    // Update the book
    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, genre = ?, best_selling = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $author, $genre, $best_selling, $book_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
        } else {
            // No rows affected might mean no changes were made
            echo json_encode(['success' => true, 'message' => 'Book updated successfully (no changes detected)']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating book: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Only PUT and POST methods allowed']);
}

$conn->close();
?>

