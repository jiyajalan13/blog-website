<?php
require_once "../config/db.php";

if (!isset($_SESSION['user'])) {
    die("Login required");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = intval($_POST['comment_id']);
    $post_id = intval($_POST['post_id']);
    
    // Verify the logged-in user is actually the author of the post
    $stmt = $conn->prepare("SELECT author_id FROM posts WHERE id=?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();

    if ($post && $_SESSION['user']['id'] == $post['author_id']) {
        // Delete the comment
        $del_stmt = $conn->prepare("DELETE FROM comments WHERE id=?");
        $del_stmt->bind_param("i", $comment_id);
        $del_stmt->execute();
    }
    
    // Redirect back to the post
    header("Location: ../posts/single.php?id=" . $post_id);
    exit();
}