<?php
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user'])) {
    die("Login required");
}

$post_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT author_id FROM posts WHERE id=?");
$stmt->bind_param("i", $post_id);
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

$stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
$stmt->bind_param("i", $post_id);
$stmt->execute();

header("Location: dashboard.php");
exit();