<?php
require_once __DIR__ . "/../config/db.php";

// 1. Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

// 2. Role verification (assuming this is an admin feature)
if ($_SESSION['user']['role'] !== 'admin') {
    die("Access Denied");
}

// 3. Process the form BEFORE outputting any HTML/headers
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author_id = $_SESSION['user']['id'];

    // Prevent submitting just empty spaces
    if (empty($title) || empty($content)) {
        $error = "Title and content cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $author_id);
        $stmt->execute();

        header("Location: ../index.php");
        exit();
    }
}

// 4. Now it is safe to include the HTML layout
require_once __DIR__ . "/../includes/header.php";
?>

<h2>Create New Post</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="content" rows="8" placeholder="Content" required></textarea>
    <button type="submit">Publish</button>
</form>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>