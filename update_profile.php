<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Update query
    $sql = "UPDATE Users SET Username = '$username', Email = '$email'";
    if ($password) {
        $sql .= ", Password = '$password'";
    }
    $sql .= " WHERE Id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        // Update session username
        $_SESSION['username'] = $username;
        header("Location: profile.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
