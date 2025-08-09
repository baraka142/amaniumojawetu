<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Connect to Neon DB
$dsn = getenv('STORAGE_DATABASE_URL');
$conn = pg_connect($dsn);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Check if user exists
$query = "SELECT * FROM users WHERE username = $1 AND email = $2";
$result = pg_query_params($conn, $query, [$username, $email]);

if (pg_num_rows($result) > 0) {
    echo json_encode(['success' => true, 'message' => 'Login successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
?>
