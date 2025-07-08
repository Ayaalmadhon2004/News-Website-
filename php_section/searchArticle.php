<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "news_db");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT article_id, title, content, image_url, published_date FROM articles WHERE title LIKE ? ORDER BY published_date DESC");
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("s", $likeSearch);
} else {
    $stmt = $conn->prepare("SELECT article_id, title, content, image_url, published_date FROM articles ORDER BY published_date DESC");
}

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
