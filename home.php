<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT Posts.*, Users.Username, 
           (SELECT COUNT(*) FROM Likes WHERE Likes.Post_id = Posts.Id) AS like_count,
           (SELECT COUNT(*) FROM Comments WHERE Comments.Post_id = Posts.Id) AS comment_count
        FROM Posts 
        INNER JOIN Users ON Posts.User_id = Users.Id 
        ORDER BY Created_at DESC";
$posts = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Hub - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS Variables for Theme */
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

        /* Reset & Base Styles */
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
            overflow-x: hidden;
        }

        /* Header Styles */
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

        .logo-icon {
            font-size: 2rem;
            color: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
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

        /* Main Container */
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Welcome Section */
        .welcome-section {
            background: var(--secondary-gradient);
            padding: 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform var(--transition-speed);
        }

        .welcome-section:hover {
            transform: translateY(-5px);
        }

        .welcome-text {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .welcome-text h1 {
            font-size: 1.8rem;
            color: var(--accent-color);
            font-weight: 700;
        }

        .welcome-text p {
            color: var(--text-secondary);
        }

        /* Post Form */
        .post-form {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            transition: all var(--transition-speed);
        }

        .post-form:focus-within {
            transform: scale(1.01);
            box-shadow: var(--shadow-lg);
        }

        textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--border-radius-md);
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
            margin-bottom: 1rem;
            transition: all var(--transition-speed);
            font-size: 1rem;
        }

        textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .submit-button {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all var(--transition-speed);
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Posts Section */
        .posts-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .post {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            transition: all var(--transition-speed);
        }

        .post:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .post-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: var(--shadow-sm);
        }

        .post-meta {
            flex-grow: 1;
        }

        .post-author {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .post-time {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .post-content {
            margin: 1rem 0;
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--text-primary);
        }

        .post-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .action-button {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: var(--border-radius-sm);
            background: var(--secondary-gradient);
            color: var(--accent-color);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all var(--transition-speed);
        }

        .action-button:hover {
            transform: translateY(-2px);
            background: #e0f2fe;
        }

        .action-button i {
            font-size: 1.1rem;
        }

        /* Comments */
        .comments-section {
            margin-top: 1rem;
        }

        .comment {
            background: var(--secondary-gradient);
            padding: 1rem;
            border-radius: var(--border-radius-md);
            margin-top: 1rem;
            transition: all var(--transition-speed);
        }

        .comment:hover {
            transform: translateX(5px);
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .comment-author {
            font-weight: 600;
            color: var(--accent-color);
        }

        .comment-time {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Footer */
        footer {
            background: var(--card-bg);
            padding: 2rem 0;
            text-align: center;
            margin-top: 3rem;
            box-shadow: var(--shadow-lg);
        }

        footer p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Responsive Design */
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

            nav a {
                padding: 0.6rem 1rem;
            }

            .welcome-section {
                padding: 1.5rem;
            }

            .post-actions {
                flex-direction: column;
            }

            .action-button {
                width: 100%;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .post {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-globe logo-icon"></i>
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
        <div class="welcome-section">
            <div class="welcome-text">
                <h1>Welcome back, <?php echo $_SESSION['username']; ?>! 👋</h1>
                <p>Share your thoughts with the community</p>
            </div>
            <i class="fas fa-stars" style="font-size: 2rem; color: var(--accent-color);"></i>
        </div>

        <div class="post-form">
            <form method="POST" action="create_post.php">
                <textarea name="content" placeholder="What's on your mind today?" required></textarea>
                <button type="submit" class="submit-button">
                    <i class="fas fa-paper-plane"></i>
                    Share Post
                </button>
            </form>
        </div>

        <div class="posts-section">
            <h2 class="section-title">
                <i class="fas fa-stream"></i>
                Recent Posts
            </h2>
            
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="post">
                    <div class="post-header">
                        <div class="post-avatar">
                            <?php echo substr($post['Username'], 0, 1); ?>
                        </div>
                        <div class="post-meta">
                            <div class="post-author"><?php echo $post['Username']; ?></div>
                            <div class="post-time">
                                <i class="far fa-clock"></i>
                                <?php echo date('F j, Y, g:i a', strtotime($post['Created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="post-content">
                        <?php echo $post['Content']; ?>
                    </div>

                    <div class="post-actions">
                        <form method="POST" action="like_comment.php" style="flex: 1;">
                            <input type="hidden" name="action" value="like">
                            <input type="hidden" name="post_id" value="<?php echo $post['Id']; ?>">
                            <button type="submit" class="action-button">
                                <i class="fas fa-heart"></i>
                                <?php echo $post['like_count']; ?> Likes
                            </button>
                        </form>

                        <form method="POST" action="view_comments.php" style="flex: 1;">
                            <input type="hidden" name="post_id" value="<?php echo $post['Id']; ?>">
                            <button type="submit" class="action-button">
                                <i class="fas fa-comment"></i>
                                <?php echo $post['comment_count']; ?> Comments
                            </button>
                        </form>
                    </div>

                    <div class="comments-section">
                        <?php
                        $comments_sql = "SELECT Comments.*, Users.Username FROM Comments 
                                       INNER JOIN Users ON Comments.User_id = Users.Id 
                                       WHERE Post_id = " . $post['Id'] . " ORDER BY Created_at DESC";
                        $comments = $conn->query($comments_sql);
                        ?>
                        <?php while ($comment = $comments->fetch_assoc()): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <i class="fas fa-user-circle"></i>
                                        <?php echo $comment['Username']; ?>
                                    </div>
                                    <div class="comment-time">
                                        <i class="far fa-clock"></i>
                                        <?php echo date('F j, Y, g:i a', strtotime($comment['Created_at'])); ?>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <?php echo $comment['Content']; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <p>© 2025 Social Hub | Created with <i class="fas fa-heart" style="color: #e11d48;"></i> by Asif Ahamed</p>
    </footer>
</body>
</html>