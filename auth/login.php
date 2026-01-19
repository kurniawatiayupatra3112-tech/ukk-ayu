<!DOCTYPE html>
<html>
<head>
    <title>Login Gudang</title>
</head>
<body>

<h2>Login Sistem Gudang</h2>

<form method="POST" action="session.php">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>

<?php
if (isset($_GET['error'])) {
    echo "<p style='color:red;'>Username atau Password salah!</p>";
}
?>

</body>
</html>
