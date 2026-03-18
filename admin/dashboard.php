<?php
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access Denied");
}

require_once __DIR__ . "/../includes/header.php";
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<h1 style="margin-bottom:25px;">Dashboard</h1>
<a class="btn" href="create-post.php">Create New Post</a>

<div style="margin-top:30px;">
<?php while ($post = $result->fetch_assoc()): ?>
<div class="post-card">
    <h3><?= htmlspecialchars($post['title']); ?></h3>
    <?php
    $isOwner = $_SESSION['user']['id'] == $post['author_id'];
    $isAdmin = $_SESSION['user']['role'] === 'admin';
    ?>
    <div style="margin-top:15px;">
        <?php if ($isOwner || $isAdmin): ?>
            <a class="btn" href="edit-post.php?id=<?= $post['id']; ?>">Edit</a>
        <?php endif; ?>

        <?php if ($isOwner || $isAdmin): ?>
            <a class="btn" style="background:#dc2626;" 
               href="delete-post.php?id=<?= $post['id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this post?');">
               Delete
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endwhile; ?>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
