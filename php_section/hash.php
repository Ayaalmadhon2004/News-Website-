<?php
$password = "finalAdmin";
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed;
?>

