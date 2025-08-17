<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' || $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // If JSON data is not available, try form data or URL parameters
    if (!$data) {
        $data = $_POST;
        if (empty($data) && isset($_GET['id'])) {
            $data['id'] = $_GET['id'];
        }
    }
    
    $book_id = isset($data['id']) ? (int)$data['id'] : 0;
    
    // Validate input
    if ($book_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Valid book ID is required']);
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
    
    // Delete the book
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Book deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No book was deleted']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting book: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Only DELETE and POST methods allowed']);
}

$conn->close();
?>

