<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    $sql = "INSERT INTO Posts (User_id, Content) VALUES ('$user_id', '$content')";
    if ($conn->query($sql) === TRUE) {
        header("Location: home.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
