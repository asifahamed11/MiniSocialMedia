<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"]=="POST")
{
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql="INSERT INTO Users (Username,Email,Password)VALUES ('$username','$email','$password')";
    if($conn->query($sql)===TRUE)
    {
        header("Location: index.php");
    }
    else
    {
        echo "Error: ".$conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body{
            font-family:'Arial',sans-serif;
            background:linear-gradient(135deg,#667eea,#764ba2);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:0;
        }

        form{
            background:rgba(255, 255, 255, 0.95);
            padding:40px;
            border-radius:10px;
            box-shadow:0 8px 20px rgba(0, 0, 0, 0.2);
            width:100%;
            max-width:400px;
        }

        h2{
            text-align:center;
            color:#333;
            margin-bottom:30px;
            font-size:28px;
        }

        input{
            width:100%;
            padding:12px;
            margin:10px 0;
            border:2px solid #ddd;
            border-radius:6px;
            box-sizing:border-box;
            transition:border-color 0.3s ease;
        }

        input:focus{
            border-color:#667eea;
            outline:none;
        }

        button{
            width:100%;
            padding:12px;
            background:linear-gradient(135deg,#667eea,#764ba2);
            border:none;
            border-radius:6px;
            color:white;
            font-size:16px;
            cursor:pointer;
            margin-top:20px;
            transition:transform 0.2s ease;
        }

        button:hover{
            transform: translateY(-2px);
        }

        p{
            text-align:center;
            margin-top:20px;
            color:#666;
        }

        a {
            color:#667eea;
            text-decoration:none;
        }

        a:hover {
            text-decoration:none;
            color:rgb(66, 97, 236);
        }
    </style>
</head>
<body>
    <form method="POST"action="">
        <h2>Register</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="index.php">Login here</a>.</p>
    </form>
</body>
</html>
