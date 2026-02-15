// Create New Post
function createPost() {
    let content = document.getElementById("postContent").value;

    if (content.trim() === "") return;

    fetch("api/create_post.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "content=" + encodeURIComponent(content)
    })
    .then(res => res.text())
    .then(response => {
        if (response.includes("Slow down")) {
            alert(response);
            return;
        }

        document.getElementById("postContent").value = "";
        loadPosts();
    });
}

// Like Post
function likePost(postId) {
    fetch("api/toggle_like.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "post_id=" + postId
    })
    .then(() => {
        loadPosts();
    });
}

// Add Comment
function addComment(postId) {
    let input = document.getElementById("comment-" + postId);
    let content = input.value;

    if (content.trim() === "") return;

    fetch("api/add_comment.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "post_id=" + postId +
              "&content=" + encodeURIComponent(content)
    })
    .then(res => res.text())
    .then(response => {
        if (response.includes("Slow down")) {
            alert(response);
            return;
        }

        input.value = "";
        loadPosts();
    });
}

// Load Posts Automatically
function loadPosts() {
    fetch("api/fetch_posts.php")
        .then(res => res.text())
        .then(data => {
            document.getElementById("posts").innerHTML = data;
        });
}

// Load when page starts
loadPosts();
