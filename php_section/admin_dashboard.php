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

    admin-container {
      font-family: "Poppins", sans-serif;
      background-color: var(--gray);
      margin: 0;
      padding: 20px;
    }

    .dash_header {
      max-width: 95%;
      margin: auto;
      margin-bottom: 25px;
      margin-top:24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .dash_header h2 {
      color: var(--main-colorR);
      font-size: 24px;
      margin: 0;
    }

    .dash_header a button {
      background-color: var(--main-colorB);
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
    }

    .dash_header a button:hover {
      background-color:rgb(16, 83, 128);
      color: var(--white);
    }

    #delete-msg {
      color: green;
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
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
      color: white;
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
      background-color: var(--main-colorB);
      color: white;
    }

    .delete {
      background-color: #e74c3c;
      color: white;
    }

    .edit:hover {
      background-color:rgb(16, 83, 128);
    }

    .delete:hover {
      background-color: #c0392b;
    }

    @media (max-width: 768px) {
      .dash_header {
        flex-direction: column;
        align-items: flex-start;
      }

      .dash_header h2 {
        margin-bottom: 10px;
      }

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
  <link rel="stylesheet" href="../css_styling/style.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
  <nav class="navbar">
    <div class="logo">
      <a href="../Html_section/index.html" style="color: white; text-decoration: none;"><h3>Global News Network</h3></a>
    </div>

    <ul class="nav-list">
      <li><a href="../Html_section/index.html">Home</a></li>
      <li><a href="../Html_section/category.html">Category</a></li>
      <li><a href="../Html_section/article.html">Article</a></li>
      <li><a href="../Html_section/contact.php">Contact Us</a></li>
      <li><a href="../Html_section/AboutUs.html">About Us</a></li>
      <li><a href="admin_dashboard.php">Admin dash</a></li>
    </ul>
    <button type="button" onclick="window.location.href='article.html'">
      <i class="fas fa-search"></i>
     </button>
  </nav>
<div class="admin-container">

<?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted"): ?>
  <p id="delete-msg">Deleted successfully</p>
<?php endif; ?>

<div class="dash_header">
  <h2>Admin Dashboard - Article Management</h2>
  <a href="add_article.php">
    <button type="button"> Add Article</button>
  </a>
</div>

<div class="dashboard-table">
  <table>
    <tr>
      <th>Title</th>
      <th>Category</th>
      <th>Published Date</th>
      <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <tr>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= htmlspecialchars($row['category_name']); ?></td>
        <td><?= $row['published_date']; ?></td>
        <td>
          <a href="edit_article.php?id=<?= $row['article_id']; ?>" class="action edit">Edit</a>
          <a href="delete_article.php?id=<?= $row['article_id']; ?>" class="action delete" onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>

  </table>
</div>

<script>
  setTimeout(() => {
    const msg = document.getElementById("delete-msg");
    if (msg) {
      msg.style.display = "none";
    }
  }, 5000);
</script>
</div>
</body>
</html>
