document.addEventListener("DOMContentLoaded", function() {
    // Button references
    const feedBtn = document.getElementById("feedBtn");
    const postBtn = document.getElementById("postBtn");
    const postBox = document.getElementById("post-box");
    const closeBtn = document.querySelector(".close-btn");
    const feed = document.getElementById("feed");

    // Show Feed
    feedBtn.addEventListener("click", function() {
        feed.style.display = "block";
        postBox.style.display = "none";
    });

    // Show Post Box
    postBtn.addEventListener("click", function() {
        feed.style.display = "none";
        postBox.style.display = "block";
    });

    // Close Post Box
    closeBtn.addEventListener("click", function() {
        postBox.style.display = "none";
        feed.style.display = "block";
    });
});

// Submit New Post
function submitPost() {
    const content = document.getElementById("newPost").value.trim();
    if (!content) return alert("Write something!");

    fetch("api/create_post.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "content=" + encodeURIComponent(content)
    })
    .then(res => res.text())
    .then(() => {
        document.getElementById("newPost").value = "";
        document.getElementById("post-box").style.display = "none";
        document.getElementById("feed").style.display = "block";
        location.reload(); // reload feed
    });
}

// Like Post
function likePost(postId) {
    fetch("api/toggle_like.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "post_id=" + postId
    })
    .then(() => location.reload());
}

// Add Comment
function addComment(postId) {
    const input = document.getElementById("comment-" + postId);
    const content = input.value.trim();
    if (!content) return;

    fetch("api/add_comment.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "post_id=" + postId + "&content=" + encodeURIComponent(content)
    })
    .then(() => {
        input.value = "";
        location.reload();
    });
}
