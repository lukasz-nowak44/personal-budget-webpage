<?php

session_start();

require_once 'database.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$_SESSION['given_signin_username'] = $_POST['username'];

if ($username === '' || $password === '') {
    $_SESSION['signin_error'] = 'Username and password cannot be empty!';
    header('Location: index.php');
    exit();
}

$query = $db->prepare('SELECT id, username, password FROM users WHERE username = :username');
$query->execute([':username' => $username]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['signin_error'] = 'Incorrect username or password.';
    header('Location: index.php');
    exit();
}

session_regenerate_id(true);
$_SESSION['logged_user_id'] = $user['id'];
$_SESSION['logged_user_username'] = $user['username'];
header('Location: home.php');
exit();
