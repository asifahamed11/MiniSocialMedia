<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    if ($action === 'like') {
        $check_like = "SELECT * FROM Likes WHERE User_id='$user_id' AND Post_id='$post_id'";
        $result = $conn->query($check_like);

        if ($result->num_rows == 0) {
            // Add like
            $sql = "INSERT INTO Likes (User_id, Post_id) VALUES ('$user_id', '$post_id')";
            $conn->query($sql);
        } else {
            // Remove like
            $sql = "DELETE FROM Likes WHERE User_id='$user_id' AND Post_id='$post_id'";
            $conn->query($sql);
        }
    } elseif ($action === 'comment') {
        // Add comment
        $comment = $_POST['comment'];
        $sql = "INSERT INTO Comments (User_id, Post_id, Content) VALUES ('$user_id', '$post_id', '$comment')";
        $conn->query($sql);
    }
}

header("Location: home.php");
?>
