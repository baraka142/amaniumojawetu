<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set response header
header('Content-Type: application/json');

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

// Basic validation
if (empty($username) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Username and email are required']);
    exit;
}

// Optional: Add email format check
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Simulate login success (replace with real logic if needed)
echo json_encode(['success' => true, 'message' => 'Login successful']);
