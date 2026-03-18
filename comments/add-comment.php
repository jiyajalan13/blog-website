<?php
include "../config/db.php";

// FIXED: Ensure only logged-in users can submit comments
if (!isset($_SESSION['user'])) {
    die("Login required");
}

$post_id = intval($_POST['post_id']);
$comment = trim($_POST['comment']);
$user_id = $_SESSION['user']['id'];

if (!empty($comment)) {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    $stmt->execute();
}

header("Location: ../posts/single.php?id=" . $post_id);
exit();