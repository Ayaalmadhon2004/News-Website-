<?php
include "../Database/db.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Html_section/index.html");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM articles WHERE article_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?msg=deleted");
        exit();
    } else {
        echo "Failed to delete from DB: " . $stmt->error;
    }
} else {
    echo "Invalid article ID";
}
?>
