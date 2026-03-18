<?php
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user'])) {
    die("Login required");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    die("Post not found");
}

$isOwner = $_SESSION['user']['id'] == $post['author_id'];
$isAdmin = $_SESSION['user']['role'] === 'admin';

if (!$isOwner && !$isAdmin) {
    die("Access Denied");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']); // Added category support

    // Update query now includes the category
    $stmt = $conn->prepare("UPDATE posts SET title=?, content=?, category=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $content, $category, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

require_once __DIR__ . "/../includes/header.php";
?>

<h2>Edit Post (Admin)</h2>

<form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']); ?>" required>
    
    <select name="category" required style="width: 100%; padding: 14px; margin-bottom: 20px; border: 1px solid #cbd5e1; border-radius: 8px;">
        <option value="General" <?= $post['category'] == 'General' ? 'selected' : ''; ?>>General</option>
        <option value="Technology" <?= $post['category'] == 'Technology' ? 'selected' : ''; ?>>Technology</option>
        <option value="Lifestyle" <?= $post['category'] == 'Lifestyle' ? 'selected' : ''; ?>>Lifestyle</option>
        <option value="Coding" <?= $post['category'] == 'Coding' ? 'selected' : ''; ?>>Coding</option>
        <option value="News" <?= $post['category'] == 'News' ? 'selected' : ''; ?>>News</option>
    </select>

    <textarea name="content" rows="8" required><?= htmlspecialchars($post['content']); ?></textarea>
    <button type="submit">Update Post</button>
    <a href="dashboard.php" class="btn" style="background: #64748b; margin-left: 10px;">Cancel</a>
</form>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>