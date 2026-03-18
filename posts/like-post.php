<?php
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user'])) {
    die("Login required");
}

$user_id = $_SESSION['user']['id'];
$post_id = intval($_GET['post_id']);

// check if already liked
$stmt = $conn->prepare("SELECT * FROM likes WHERE user_id=? AND post_id=?");
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {

    $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?,?)");
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
}

header("Location: ../index.php");
exit();