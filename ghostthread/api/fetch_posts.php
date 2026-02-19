<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Generate anonymous user hash
$user_hash = md5($_SERVER['REMOTE_ADDR'] . session_id());

// Fetch posts with like count + detect if liked by current user
$sql = "
SELECT 
    posts.id,
    posts.content,
    COUNT(likes.id) AS like_count,
    MAX(likes.user_hash = ?) AS liked
FROM posts
LEFT JOIN likes ON posts.id = likes.post_id
GROUP BY posts.id
ORDER BY posts.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_hash);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    echo "<div class='post'>";
    echo "<p>" . htmlspecialchars($row['content']) . "</p>";

    // Like button
    $heart = $row['liked'] ? "❤️" : "🤍";
    echo "<button onclick='likePost({$row['id']})'>
            $heart {$row['like_count']}
          </button>";

    echo "<hr>";

    // Comments
    $cstmt = $conn->prepare(
        "SELECT content FROM comments WHERE post_id = ? ORDER BY id ASC"
    );
    $cstmt->bind_param("i", $row['id']);
    $cstmt->execute();
    $comments = $cstmt->get_result();

    echo "<div class='comments'>";
    while ($c = $comments->fetch_assoc()) {
        echo "<div class='comment'>💬 " . htmlspecialchars($c['content']) . "</div>";
    }
    echo "</div>";

    // Comment input + send icon
    echo "
        <input type='text' id='comment-{$row['id']}' placeholder='Write a comment anonymously...'>
        <button onclick='addComment({$row['id']})'>
            <i class='bi bi-send-fill'></i>
        </button>
    ";

    echo "</div>";
}

