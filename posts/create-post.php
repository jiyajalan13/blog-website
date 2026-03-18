<?php
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']); // New category field
    $author_id = $_SESSION['user']['id'];

    if (empty($title) || empty($content)) {
        $error = "Title and content cannot be empty.";
    } else {
        // Updated INSERT statement to include category
        $stmt = $conn->prepare("INSERT INTO posts (title, content, category, author_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $content, $category, $author_id);
        $stmt->execute();

        header("Location: ../index.php");
        exit();
    }
}

require_once __DIR__ . "/../includes/header.php";
?>

<h2>Create New Post</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    
    <select name="category" required style="width: 100%; padding: 14px; margin-bottom: 20px; border: 1px solid #cbd5e1; border-radius: 8px;">
        <option value="General">General</option>
        <option value="Technology">Technology</option>
        <option value="Lifestyle">Lifestyle</option>
        <option value="Coding">Coding</option>
        <option value="News">News</option>
    </select>

    <textarea name="content" rows="8" placeholder="Content" required></textarea>
    <button type="submit">Publish</button>
</form>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
