<?php
session_start();
include '../koneksi.php';

// JIKA FORM LOGIN DIKIRIM
if (isset($_POST['login'])) {

    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        if (password_verify($password, $data['password'])) {

            // BUAT SESSION
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['id_user'] = $data['id'];

            header("Location: ../index.php");
            exit;

        } else {
            header("Location: login.php?error=1");
            exit;
        }
    } else {
        header("Location: login.php?error=1");
        exit;
    }
}
