<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/header.php";

// Check if a category filter is active
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// --- PAGINATION LOGIC ---
$posts_per_page = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $posts_per_page;

if ($category_filter) {
    $total_query = $conn->prepare("SELECT COUNT(id) as total FROM posts WHERE category = ?");
    $total_query->bind_param("s", $category_filter);
    $total_query->execute();
    $total_row = $total_query->get_result()->fetch_assoc();
} else {
    $total_query = $conn->query("SELECT COUNT(id) as total FROM posts");
    $total_row = $total_query->fetch_assoc();
}

$total_posts = $total_row['total'];
$total_pages = ceil($total_posts / $posts_per_page);

if ($category_filter) {
    $stmt = $conn->prepare("
        SELECT posts.*, users.name,
        (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS like_count
        FROM posts JOIN users ON posts.author_id = users.id
        WHERE posts.category = ?
        ORDER BY posts.created_at DESC LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("sii", $category_filter, $posts_per_page, $offset);
} else {
    $stmt = $conn->prepare("
        SELECT posts.*, users.name,
        (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS like_count
        FROM posts JOIN users ON posts.author_id = users.id
        ORDER BY posts.created_at DESC LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $posts_per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) { $posts[] = $row; }
?>

<?php if ($page == 1 && !$category_filter): ?>
<div class="hero">
    <h1>Welcome to BlogLog</h1>
    <p>Read stories, share ideas, and explore thoughts.</p>
</div>
<?php endif; ?>

<h2 style="margin:30px 0;">
    <?= $category_filter ? "Category: " . htmlspecialchars($category_filter) : "Latest Posts"; ?>
    <?php if($category_filter): ?> <a href="index.php" style="font-size:1rem; margin-left:15px;">Clear Filter</a> <?php endif; ?>
</h2>

<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
    <div class="post-card">
        <h3 class="post-title"><a href="posts/single.php?id=<?= $post['id']; ?>"><?= htmlspecialchars($post['title']); ?></a></h3>
        
        <div class="post-meta">
            By <?= htmlspecialchars($post['name']); ?> • 
            <a href="index.php?category=<?= urlencode($post['category']); ?>" style="color: var(--primary);"><?= htmlspecialchars($post['category']); ?></a> • 
            👁️ <?= $post['views']; ?> Views
        </div>

        <p class="post-preview"><?= htmlspecialchars(substr($post['content'], 0, 200)); ?>...</p>

        <a class="btn" href="posts/single.php?id=<?= $post['id']; ?>">Read More</a>
        <div style="margin-top:15px; display:inline-block; margin-left:10px;">
            <a class="btn" style="background:#e11d48;" href="posts/toggle-like.php?post_id=<?= $post['id']; ?>">❤️ Like</a>
            <span style="margin-left:10px; font-weight:bold; color:#475569;"><?= $post['like_count']; ?> likes</span>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="pagination">
        <?php $cat_url = $category_filter ? "&category=" . urlencode($category_filter) : ""; ?>
        <?php if ($page > 1): ?>
            <a class="btn" href="?page=<?= $page - 1 . $cat_url; ?>">← Previous</a>
        <?php endif; ?>
        <span class="page-info">Page <?= $page; ?> of <?= $total_pages; ?></span>
        <?php if ($page < $total_pages): ?>
            <a class="btn" href="?page=<?= $page + 1 . $cat_url; ?>">Next →</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <p>No blog posts found.</p>
<?php endif; ?>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
