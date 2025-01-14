<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user = $conn->query("SELECT * FROM Users WHERE Id = '$user_id'")->fetch_assoc();
$posts = $conn->query("SELECT * FROM Posts WHERE User_id = '$user_id' ORDER BY Created_at DESC");
$likes = $conn->query("SELECT COUNT(*) AS like_count FROM Likes WHERE User_id = '$user_id'")->fetch_assoc();
$comments = $conn->query("SELECT COUNT(*) AS comment_count FROM Comments WHERE User_id = '$user_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Hub - Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --secondary-gradient: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            --accent-color: #4f46e5;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --background-main: #f8fafc;
            --card-bg: #ffffff;
            --border-radius-lg: 16px;
            --border-radius-md: 12px;
            --border-radius-sm: 8px;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--background-main);
            color: var(--text-primary);
            line-height: 1.6;
        }

        header {
            background: var(--primary-gradient);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo h1 {
            font-size: 1.8rem;
            color: white;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        nav {
            display: flex;
            gap: 1.5rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.2rem;
            border-radius: var(--border-radius-sm);
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .profile-card {
            background: var(--card-bg);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            font-weight: bold;
            box-shadow: var(--shadow-md);
        }

        .profile-info h2 {
            font-size: 1.8rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .profile-email {
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: var(--secondary-gradient);
            padding: 1.5rem;
            border-radius: var(--border-radius-md);
            text-align: center;
            transition: transform var(--transition-speed);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .stat-label {
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        .edit-form {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            margin: 2rem 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--border-radius-md);
            font-size: 1rem;
            transition: all var(--transition-speed);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .update-button {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all var(--transition-speed);
        }

        .update-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .posts-section {
            margin-top: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .post {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius-lg);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: transform var(--transition-speed);
        }

        .post:hover {
            transform: translateY(-3px);
        }

        .post-content {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        .post-time {
            color: var(--text-secondary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        footer {
            background: var(--card-bg);
            padding: 2rem 0;
            text-align: center;
            margin-top: 3rem;
            box-shadow: var(--shadow-lg);
        }

        footer p {
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            nav {
                width: 100%;
                justify-content: center;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-globe"></i>
                <h1>Mini Social Media</h1>
            </div>
            <nav>
                <a href="home.php"><i class="fas fa-home"></i> Home</a>
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo substr($user['Username'], 0, 1); ?>
                </div>
                <div class="profile-info">
                    <h2><?php echo $user['Username']; ?></h2>
                    <div class="profile-email">
                        <i class="fas fa-envelope"></i>
                        <?php echo $user['Email']; ?>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $likes['like_count']; ?></div>
                    <div class="stat-label">
                        <i class="fas fa-heart"></i> Total Likes
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $comments['comment_count']; ?></div>
                    <div class="stat-label">
                        <i class="fas fa-comment"></i> Total Comments
                    </div>
                </div>
            </div>
        </div>

        <div class="edit-form">
            <h3 class="section-title">
                <i class="fas fa-edit"></i>
                Edit Profile
            </h3>
            <form method="POST" action="update_profile.php">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $user['Username']; ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $user['Email']; ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>New Password (optional)</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current password" class="form-input">
                </div>
                <button type="submit" class="update-button">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>

        <div class="posts-section">
            <h3 class="section-title">
                <i class="fas fa-file-alt"></i>
                Your Posts
            </h3>
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <div class="post">
                        <div class="post-content"><?php echo $post['Content']; ?></div>
                        <div class="post-time">
                            <i class="far fa-clock"></i>
                            <?php echo date('F j, Y, g:i a', strtotime($post['Created_at'])); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="post">
                    <p>No posts yet. Share your thoughts with the community!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>
            © 2025 Social Hub | Created with <i class="fas fa-heart" style="color: #e11d48;"></i> by Asif Ahamed
        </p>
    </footer>
</body>
</html>