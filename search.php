<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/header.php";

$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$posts = [];

if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $stmt = $conn->prepare("
        SELECT posts.*, users.name,
        (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS like_count
        FROM posts
        JOIN users ON posts.author_id = users.id
        WHERE posts.title LIKE ? OR posts.content LIKE ?
        ORDER BY posts.created_at DESC
    ");
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>

<h2>Search Results for "<?= htmlspecialchars($search_query); ?>"</h2>

<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
    <div class="post-card" style="margin-top: 20px;">
        <h3 class="post-title"><a href="posts/single.php?id=<?= $post['id']; ?>"><?= htmlspecialchars($post['title']); ?></a></h3>
        <div class="post-meta">
            By <?= htmlspecialchars($post['name']); ?> • Category: <?= htmlspecialchars($post['category']); ?>
        </div>
        <p class="post-preview"><?= htmlspecialchars(substr($post['content'], 0, 150)); ?>...</p>
        <a class="btn" href="posts/single.php?id=<?= $post['id']; ?>">Read More</a>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="margin-top: 20px;">No posts found matching your search.</p>
<?php endif; ?>

<?php require_once __DIR__ . "/includes/footer.php"; ?>