<?php
include "../Database/db.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Html_section/index.html");
    exit();
}

$sql = 'SELECT * FROM articles JOIN categories ON articles.category_id = categories.category_id';
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background-color: var(--gray);
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: var(--main-colorR);
      margin-bottom: 30px;
    }

    .dashboard-table {
      background-color: var(--white);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      max-width: 95%;
      margin: auto;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ccc;
      text-align: center;
      font-size: 14px;
    }

    th {
      background-color: var(--main-colorB);
      color: var(--main-colorB);
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    a.action {
      padding: 6px 12px;
      text-decoration: none;
      border-radius: 5px;
      font-size: 13px;
      margin: 0 4px;
      display: inline-block;
    }

    .edit {
      background-color:rgb(37, 108, 156);
      color: white;
    }

    .delete {
      background-color: #e74c3c;
      color: white;
    }

    .edit:hover {
      background-color: #217dbb;
    }

    .delete:hover {
      background-color: #c0392b;
    }

    @media (max-width: 768px) {
      th, td {
        font-size: 12px;
        padding: 8px;
      }
      a.action {
        padding: 4px 8px;
        font-size: 11px;
      }
    }
  </style>
</head>
<body>

<h2>Admin Dashboard - Article Management</h2>

<div class="dashboard-table">
  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Category</th>
      <th>Published Date</th>
      <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <tr>
        <td><?= $row['article_id']; ?></td>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= htmlspecialchars($row['category_name']); ?></td>
        <td><?= $row['published_date']; ?></td>
        <td>
          <a href="edit_user.php?id=<?= $row['article_id']; ?>" class="action edit">Edit</a>
          <a href="delete_article.php?id=<?= $row['article_id']; ?>" class="action delete" onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>

  </table>
</div>

</body>
</html>
