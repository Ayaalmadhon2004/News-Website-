<?php
include '../Database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // تحقق إذا كان اسم المستخدم موجود مسبقاً
    $check_sql = "SELECT user_id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    if (!$check_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $check_stmt->bind_param('s', $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "Username already exists.";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('ss', $username, $password);

        if ($stmt->execute()) {
            $success = "Account created successfully! <a href='login.php'>Login here</a>";
        } else {
            $error = "Error occurred during registration: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* ... الكود نفسه لتنسيق الصفحة ... */
  </style>
</head>
<body>

  <div class="signup-form">
    <h2>Create New Account</h2>

    <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Sign Up</button>
    </form>
  </div>

</body>
</html>
