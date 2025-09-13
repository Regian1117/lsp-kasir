<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
}
include '../includes/koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // simpan session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Username/password salah";
    }
}
?>

<form method="POST">
    <label for="username">Username : </label>
    <input type="text" name="username" id="username" placeholder="Username" required>
    <label for="password">Password : </label>
    <input type="password" name="password" id="password" placeholder="password" required>
    <button type="submit" name="login">Login</button>
</form>