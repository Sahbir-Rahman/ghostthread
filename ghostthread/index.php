<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GhostThread</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

<h1 class="logo">
    <span class="ghost">👻</span> GhostThread
</h1>

<p class="tagline">No Login. No Profile. Just Conversation.</p>

<div class="sidebar">
    <button id="feedBtn">Feed</button>
    <button id="postBtn">Post</button>
</div>

<!-- Feed Section -->
<div id="feed">
    <?php
    require_once "config/db.php";
    $user_hash = md5($_SERVER['REMOTE_ADDR'] . session_id());

    $sql = "SELECT posts.id, posts.content, 
                   COUNT(likes.id) AS like_count,
                   MAX(likes.user_hash=?) AS liked
            FROM posts
            LEFT JOIN likes ON posts.id = likes.post_id
            GROUP BY posts.id
            ORDER BY posts.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<div class='post' id='post-{$row['id']}'>";
        echo "<p>" . htmlspecialchars($row['content']) . "</p>";

        // Like button
        $heart = $row['liked'] ? "❤️" : "🤍";
        echo "<button onclick='likePost({$row['id']})'>{$heart} {$row['like_count']}</button>";

        echo "<hr>";

        // Comments
        echo "<div class='comments'>";
        $cstmt = $conn->prepare("SELECT content FROM comments WHERE post_id=? ORDER BY id ASC");
        $cstmt->bind_param("i", $row['id']);
        $cstmt->execute();
        $comments = $cstmt->get_result();

        while ($c = $comments->fetch_assoc()) {
            echo "<div class='comment'>💬 " . htmlspecialchars($c['content']) . "</div>";
        }
        echo "</div>";

        // Comment input
        echo "
            <input type='text' id='comment-{$row['id']}' placeholder='Write a comment anonymously...'>
            <button onclick='addComment({$row['id']})'>
                <i class='bi bi-send-fill'></i>
            </button>
        ";
        echo "</div>";
    }
    ?>
</div>

<!-- Post Box -->
<div id="post-box" style="display:none;">
    <button class="close-btn">✖</button>
    <textarea id="newPost" placeholder="Write your anonymous thought..."></textarea>
    <button onclick="submitPost()">
        <i class="bi bi-send-fill"></i>
    </button>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
