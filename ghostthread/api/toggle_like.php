<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$post_id = (int)$_POST['post_id'];
$user_hash = md5($_SERVER['REMOTE_ADDR'] . session_id());

//check if already liked
$check = $conn->prepare(
    "SELECT id FROM likes WHERE post_id = ? AND user_hash = ?"
);
$check->bind_param("is", $post_id, $user_hash);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    //unlik (delete)
    $delete = $conn-> prepare(
        "DELETE FROM likes WHERE post_id = ? AND user_hash = ?"
    );
    $delete->bind_param("is", $post_id, $user_hash);
    $delete->execute();
}else {
    $insert = $conn->prepare(
        "INSERT INTO likes (post_id, user_hash) VALUES (?, ?)"
    );
    $insert->bind_param("is", $post_id, $user_hash);
    $insert->execute();
}

echo "success";