<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GhostThread</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h1>👻 GhostThread</h1>
<p class="tagline">No Login. No Profile. Just Conversation.</p>

<div class="post-box">
    <textarea id="postContent" placeholder="Say something anonymously..."></textarea>
    <button onclick="createPost()">Post</button>
</div>

<div id="posts"></div>

<script src="assets/js/main.js"></script>
</body>
</html>
