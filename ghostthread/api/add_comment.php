<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$post_id = (int)$_POST['post_id'];
$content = trim($_POST['content']);

if ($content === "") exit;

// Rate limit: 5 seconds per comment
if (isset($_SESSION['last_comment_time'])) {
    if (time() - $_SESSION['last_comment_time'] < 5) {
        die("Slow down!");
    }
}

$user_hash = md5($_SERVER['REMOTE_ADDR'] . session_id());

$stmt = $conn->prepare(
    "INSERT INTO comments (post_id, content, user_hash) VALUES (?, ?, ?)"
);
$stmt->bind_param("iss", $post_id, $content, $user_hash);
$stmt->execute();

$_SESSION['last_comment_time'] = time();
