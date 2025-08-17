<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT id, title, author, genre, best_selling, created_at FROM books ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $books = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'author' => $row['author'],
                'genre' => $row['genre'],
                'best_selling' => (bool)$row['best_selling'],
                'created_at' => $row['created_at']
            );
        }
    }
    
    echo json_encode(['success' => true, 'data' => $books]);
} else {
    echo json_encode(['success' => false, 'message' => 'Only GET method allowed']);
}

$conn->close();
?>

