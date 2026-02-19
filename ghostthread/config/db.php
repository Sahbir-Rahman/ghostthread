<?php
$conn = new mysqli("localhost", "root", "M21SMTOO", "ghostthread");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
