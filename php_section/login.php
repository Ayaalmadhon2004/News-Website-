<?php
include '../Database/db.php';
session_start();

$error = ""; // Initialize error to avoid undefined variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']=$user['user_id'];
            $_SESSION['role']=$user['role'];
            $_SESSION['user']=$user['username'];

            if($user['role']==='admin'){
              header("Location: ../php_section/admin_dashboard.php");
              } else {
              header("Location: ../Html_section/index.html");
            }
            exit;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    :root {
      --main-colorB: #1e90ff;
      --main-colorR: #ff4d4d;
      --white: #ffffff;
    }

    body {
      font-family: "Poppins", sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 0;
    }

    .login-form {
      max-width: 400px;
      margin: 100px auto;
      background-color: var(--white);
      border-radius: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 30px;
      text-align: center;
      color: var(--main-colorB);
    }

    .login-form h2 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 20px;
      color: var(--main-colorR);
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      margin: 12px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    .login-form input[type="text"]:focus,
    .login-form input[type="password"]:focus {
      outline: none;
      border-color: var(--main-colorB);
    }

    .login-form button {
      background-color: var(--main-colorR);
      color: #fff;
      border: none;
      padding: 12px 0;
      width: 100%;
      border-radius: 25px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 15px;
      transition: background-color 0.3s ease;
    }

    .login-form button:hover {
      background-color: darkred;
    }

    .login-form p.error {
      color: var(--main-colorR);
      background-color: #ffe6e6;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-weight: 500;
    }
  </style>
</head>
<body>

  <div class="login-form">
    <h2>Login</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
  </div>

</body>
</html>
