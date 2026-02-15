<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (isset($_SESSION['last_post_time'])) {
    if (time() - $_SESSION['last_post_time'] < 15) {
        die("Slow down! Wait 15 seconds.");
    }
}

$content = trim($_POST['content']);
if ($content === "") exit;

$stmt = $conn->prepare("INSERT INTO posts (content) VALUES (?)");
$stmt->bind_param("s", $content);
$stmt->execute();

$_SESSION['last_post_time'] = time();
