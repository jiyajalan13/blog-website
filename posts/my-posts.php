<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/header.php";

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Fetch only the posts created by the logged-in user
$stmt = $conn->prepare("
    SELECT posts.*, 
    (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS like_count
    FROM posts
    WHERE author_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin: 30px 0;">
    <h1>My Posts</h1>
    <a class="btn" href="create-post.php">+ Create New Post</a>
</div>

<?php if (count($posts) > 0): ?>

    <?php foreach ($posts as $post): ?>
    <div class="post-card">
        <h3 class="post-title">
            <a href="single.php?id=<?= $post['id']; ?>">
                <?= htmlspecialchars($post['title']); ?>
            </a>
        </h3>

        <div class="post-meta">
            Published on <?= date("F j, Y", strtotime($post['created_at'])); ?>
        </div>

        <p class="post-preview">
            <?= htmlspecialchars(substr($post['content'], 0, 200)); ?>...
        </p>

        <a class="btn" href="single.php?id=<?= $post['id']; ?>">View</a>
        <a class="btn" style="background:#f59e0b; margin-left:10px;" href="edit-post.php?id=<?= $post['id']; ?>">✏️ Edit</a>
        
        <span style="margin-left:15px; font-weight:bold; color:#475569;">
            ❤️ <?= $post['like_count']; ?> likes
        </span>
    </div>
    <?php endforeach; ?>

<?php else: ?>
    <div style="background: #f8fafc; padding: 40px; text-align: center; border-radius: 8px; border: 1px dashed #cbd5e1;">
        <h3 style="color: #64748b; margin-bottom: 15px;">You haven't published any posts yet.</h3>
        <a class="btn" href="create-post.php">Write your first post</a>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
