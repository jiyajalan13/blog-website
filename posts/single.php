<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/header.php";

if (!isset($_GET['id'])) { die("Post not found"); }

$id = intval($_GET['id']);

// NEW: Increment the view counter by 1 every time this page loads
$conn->query("UPDATE posts SET views = views + 1 WHERE id = $id");

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) { die("Post not found"); }
?>

<h1><?= htmlspecialchars($post['title']); ?></h1>

<div class="post-meta" style="margin-bottom: 20px;">
    Category: <a href="../index.php?category=<?= urlencode($post['category']); ?>" style="color: var(--primary);"><?= htmlspecialchars($post['category']); ?></a> 
    • Views: 👁️ <?= $post['views']; ?>
</div>

<p style="margin-top: 20px; font-size: 1.1rem;"><?= nl2br(htmlspecialchars($post['content'])); ?></p>
<hr>

<h3>Comments</h3>

<?php
$comments = $conn->prepare("
    SELECT comments.*, users.name 
    FROM comments 
    JOIN users ON comments.user_id = users.id 
    WHERE post_id = ?
");
$comments->bind_param("i", $id);
$comments->execute();
$result = $comments->get_result();

while ($comment = $result->fetch_assoc()):
?>
    <div style="margin-bottom: 10px; padding: 15px; border-bottom: 1px solid #eee; background: #f8fafc; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between;">
            <strong><?= htmlspecialchars($comment['name']); ?>:</strong>
            
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $post['author_id']): ?>
                <form action="../comments/delete-comment.php" method="POST" style="margin: 0;">
                    <input type="hidden" name="comment_id" value="<?= $comment['id']; ?>">
                    <input type="hidden" name="post_id" value="<?= $id; ?>">
                    <button type="submit" style="background: #ef4444; padding: 3px 8px; font-size: 0.8rem;" onclick="return confirm('Delete this comment?');">Delete</button>
                </form>
            <?php endif; ?>
        </div>
        <p style="margin-top: 5px;"><?= htmlspecialchars($comment['comment']); ?></p>
    </div>
<?php endwhile; ?>

<?php if (isset($_SESSION['user'])): ?>
<form action="../comments/add-comment.php" method="POST" style="margin-top: 20px;">
    <input type="hidden" name="post_id" value="<?= $id; ?>">
    <textarea name="comment" placeholder="Write a comment..." required></textarea>
    <button type="submit">Add Comment</button>
</form>
<?php else: ?>
<p style="margin-top: 20px; font-weight: bold;">Please <a href="../auth/login.php">login</a> to comment.</p>
<?php endif; ?>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>