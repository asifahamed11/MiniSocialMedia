<?php
include 'db.php';
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $email=$_POST['email'];
    $password=$_POST['password'];
    $sql="SELECT * FROM Users WHERE Email='$email'";
    $result=$conn->query($sql);
    if ($result->num_rows>0) 
    {
        $row=$result->fetch_assoc();
        if (password_verify($password,$row['Password'])) 
        {
            $_SESSION['user_id']=$row['Id'];
            $_SESSION['username']=$row['Username'];
            header("Location: home.php");
        } 
        else 
        {
            echo "Invalid credentials.";
        }
    } 
    else
    {
        echo "No user found with this email.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body{
            font-family:'Arial',sans-serif;
            background:linear-gradient(120deg,#7f7fd5,#86a8e7,#91eae4);
            height:100vh;
            margin:0;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        form{
            background:rgba(255,255,255,0.9);
            padding:40px;
            border-radius:10px;
            box-shadow:0 0 20px rgba(0, 0, 0, 0.1);
            width:100%;
            max-width:400px;
        }
        h2{
            text-align:center;
            color:#333;
            margin-bottom:30px;
        }
        input{
            width:100%;
            padding:12px;
            margin:10px 0;
            border:1px solid #ddd;
            border-radius:5px;
            box-sizing:border-box;
            transition:border-color 0.3s ease;
        }
        input:focus{
            border-color:#7f7fd5;
            outline:none;
        }
        a{
            color:#7f7fd5;
            text-decoration:none;
        }
        a:hover{
            text-decoration:none;
            color:rgb(82, 82, 216);
        }
        p{
            text-align:center;
            margin-top:20px;
            color:#666;
        }
        button{
            width:100%;
            padding:12px;
            background:#7f7fd5;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
            font-size:16px;
            transition:background 0.3s ease;
        }
        button:hover{
            background:#6c6cbe;
        }

    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Welcome Back</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>
