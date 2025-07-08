<?php
include '../Database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "Username already exists.";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
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
    :root {
      --main-colorB: #1e90ff;
      --main-colorR: #ff4d4d;
      --white: #ffffff;
    }

    body {
      font-family: "Poppins", sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding-top: 100px;
    }

    .signup-form {
      max-width: 400px;
      margin: 60px auto;
      background-color: var(--white);
      border-radius: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 30px;
      text-align: center;
      color: var(--main-colorB);
    }

    .signup-form h2 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 20px;
      color: var(--main-colorR);
    }

    .signup-form input[type="text"],
    .signup-form input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      margin: 12px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    .signup-form input:focus {
      outline: none;
      border-color: var(--main-colorB);
    }

    .signup-form button {
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

    .signup-form button:hover {
      background-color: darkred;
    }

    .signup-form p.message {
      padding: 10px;
      border-radius: 10px;
      font-weight: 500;
      margin-bottom: 15px;
    }

    .signup-form .error {
      background-color: #ffe6e6;
      color: var(--main-colorR);
    }

    .signup-form .success {
      background-color: #e7fbe7;
      color: #2e8b57;
    }

    .signup-form a {
      color: var(--main-colorB);
      text-decoration: underline;
      font-weight: 500;
    }

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
