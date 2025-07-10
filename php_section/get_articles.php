<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

// Connect to database
$conn = new mysqli("localhost", "root", "", "news_db");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Check for category_id
if (!isset($_GET['category_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing category_id"]);
    exit();
}


$category_id = intval($_GET['category_id']);

// Fetch articles for that category
$stmt = $conn->prepare("SELECT article_id, title, content, image_url, published_date FROM articles WHERE category_id = ? ORDER BY published_date DESC");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

$articles = [];
while ($row = $result->fetch_assoc()) {
    $articles[] = $row;
}

echo json_encode($articles);

$stmt->close();
$conn->close();
?>
