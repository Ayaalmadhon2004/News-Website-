<?php
include "../Database/db.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Html_section/index.html");
    exit();
}

$categories_result = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = intval($_POST['category_id']);
    $author_id = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");

    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_dir = __DIR__ . "/../assets/images/";
        $target_file = $target_dir . $image_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = "assets/images/" . $image_name;
            } else {
                $error = "Failed to upload the image.";
            }
        } else {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        }
    }

    if (!empty($title) && !empty($content) && $category_id > 0 && empty($error)) {
        $stmt = $conn->prepare('INSERT INTO articles (title, content, category_id, published_date, author_id, image_url) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssisss', $title, $content, $category_id, $date, $author_id, $image_url);

        if ($stmt->execute()) {
            header("Location: ../php_section/admin_dashboard.php?msg=added");
            exit();
        } else {
            $error = "Failed to add article: " . $stmt->error;
        }
    } else {
        if (!isset($error)) {
            $error = "Please fill in all required fields.";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Article</title>
  <link rel="stylesheet" href="../css_styling/style.css"/>
  <style>
    :root {
      --error-color: #e74c3c;
    }

    body {
      font-family: "Poppins", sans-serif;
      background-color: var(--gray);
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: var(--main-colorB);
      margin-bottom: 20px;
    }

    form {
      max-width: 600px;
      margin: auto;
      background: var(--white);
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 12px;
    }

    input[type="text"],
    textarea,
    select,
    input[type="file"] {
      width: 100%;
      margin-top: 6px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    button {
      margin-top: 20px;
      background-color: var(--main-colorG);
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      font-size: 14px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #27ae60;
    }

    .error {
      background-color: var(--error-color);
      color: white;
      padding: 10px;
      text-align: center;
      border-radius: 6px;
      margin-bottom: 15px;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      form {
        padding: 15px;
      }

      button {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>

  <h2>Add New Article</h2>

  <?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Content:</label>
    <textarea name="content" rows="6" required></textarea>

    <label>Category:</label>
    <select name="category_id" required>
      <option value="">Select a category</option>
      <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>
        <option value="<?= $cat['category_id'] ?>">
          <?= htmlspecialchars($cat['category_name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Add Article</button>
  </form>

</body>
</html>
