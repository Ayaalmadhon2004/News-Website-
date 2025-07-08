<?php
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars(trim($_POST["first_name"]));
    $lastName = htmlspecialchars(trim($_POST["last_name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]));

    if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
        $errorMessage = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        $successMessage = "Thank you $firstName! We received your message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="../css_styling/style.css"/>
</head>
<body>
   <nav class="navbar">
    <div class="logo">
      <a href="../index.php" style="color: white; text-decoration: none;"><h3>Global News Network</h3></a>
    </div>

    <ul class="nav-list">
      <li><a href="index.html">Home</a></li>
      <li><a href="category.html">Category</a></li>
      <li><a href="article.html">Article</a></li>
      <li><a href="contact.php">Contact Us</a></li>
      <li><a href="AboutUs.html">About Us</a></li>
    </ul>
    <button type="button" onclick="window.location.href='article.html'">
      <i class="fas fa-search"></i>
     </button>
  </nav>
  <div class="contact-body">
  <form class="contact-form" method="POST" action="">
    <h1>Contact Us</h1>

    <?php if (!empty($successMessage)): ?>
      <div class="message success"><?= $successMessage ?></div>
    <?php elseif (!empty($errorMessage)): ?>
      <div class="message error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required />

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required />

    <label for="message">Message:</label>
    <textarea id="message" name="message" placeholder="Enter your message here" required></textarea>

    <button type="submit">Submit</button>
  </form>
  </div>
</body>
</html>
