<?php
include "../Database/db.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Html_section/index.html");
    exit();
}

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM articles WHERE article_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    die("❌ Article not found.");
}

$categories_result = $conn->query("SELECT * FROM categories");

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = trim($_POST['title']);
    $new_category = (int)$_POST['category_id'];
    $new_date = $_POST['published_date'];

    $update_sql = "UPDATE articles SET title = ?, category_id = ?, published_date = ? WHERE article_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sisi", $new_title, $new_category, $new_date, $article_id);

    if ($update_stmt->execute()) {
        $success = "✅ Article updated successfully!";
        // refresh article data after update
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();
    } else {
        $error = "❌ Error: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Article</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css_styling/style.css"/>
  <style>

    body {
      font-family: "Poppins", sans-serif;
      background-color: #f2f2f2;
      padding-top: 100px;
    }

    .form-container {
      max-width: 600px;
      margin: 50px auto;
      background-color: var(--white);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    h2 {
      color: var(--main-colorB);
      margin-bottom: 20px;
    }

    input[type="text"],
    select,
    input[type="date"] {
      width: 100%;
      padding: 12px;
      margin: 12px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
    }

    button {
      background-color: var(--main-colorR);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 25px;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      margin-top: 15px;
    }

    button:hover {
      background-color: darkred;
    }

    .message {
      padding: 10px;
      margin: 10px 0;
      font-weight: 500;
      border-radius: 8px;
    }

    .success {
      background-color: #e7fbe7;
      color: #2e8b57;
    }

    .error {
      background-color: #ffe6e6;
      color: var(--main-colorR);
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Edit Article</h2>

  <?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>
  <?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>

  <form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($article['title']); ?>" required>
    
    <select name="category_id" required>
      <?php while ($cat = $categories_result->fetch_assoc()): ?>
        <option value="<?= $cat['category_id']; ?>" <?= $cat['category_id'] == $article['category_id'] ? 'selected' : ''; ?>>
          <?= htmlspecialchars($cat['category_name']); ?>
        </option>
      <?php endwhile; ?>
    </select>

    <input type="date" name="published_date" value="<?= $article['published_date']; ?>" required>

    <button type="submit">Update Article</button>
  </form>
</div>

</body>
</html>
