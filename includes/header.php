<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogLog | Share Your Story</title>
    <link rel="stylesheet" href="/blog-website/assets/css/style.css">
</head>
<body>

<nav>
    <div class="logo">
        <a href="/blog-website/index.php">Blog<span>Log</span></a>
    </div>

    <div class="nav-links">
        <form action="/blog-website/search.php" method="GET" style="display: flex; align-items: center; gap: 5px; margin-right: 15px;">
            <input type="text" name="query" placeholder="Search posts..." required style="margin: 0; padding: 6px 10px; width: 180px;">
            <button type="submit" style="padding: 6px 12px; margin: 0;">Search</button>
        </form>

        <?php if(isset($_SESSION['user'])): ?>
            <a href="/blog-website/posts/create-post.php">Create Post</a>
            <a href="/blog-website/posts/my-posts.php">My Posts</a>
            
            <?php if($_SESSION['user']['role'] === 'admin'): ?>
                <a href="/blog-website/admin/dashboard.php" class="admin-link">Admin Dashboard</a>
            <?php endif; ?>

            <div class="user-menu">
                <span class="user-name">Hi, <?= htmlspecialchars($_SESSION['user']['name']); ?></span>
                <a href="/blog-website/auth/logout.php" class="btn-logout">Logout</a>
            </div>
        <?php else: ?>
            <a href="/blog-website/auth/login.php">Login</a>
            <a href="/blog-website/auth/register.php" class="btn-register">Register</a>
        <?php endif; ?>
    </div>
</nav>
<div class="container">