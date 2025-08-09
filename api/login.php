<?php
header('Content-Type: application/json');

// Get POST data
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

// Validate input
if (empty($username) || empty($email)) {
    echo json_encode(['error' => 'Missing username or email']);
    exit;
}

// Connect to Neon using STORAGE_URL
$storageUrl = getenv('STORAGE_URL');
$conn = pg_connect($storageUrl);

if (!$conn) {
    error_log("Database connection failed");
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Query to check if user exists
$result = pg_query_params($conn,
    "SELECT * FROM users WHERE username = $1 AND email = $2",
    [$username, $email]
);

if (!$result) {
    error_log("Query failed: " . pg_last_error($conn));
    echo json_encode(['error' => 'Query error']);
    exit;
}

// Check if user was found
if (pg_num_rows($result) === 0) {
    echo json_encode(['error' => 'Login failed. Please try again.']);
    exit;
}

// Success
$user = pg_fetch_assoc($result);
echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'user' => [
        'username' => $user['username'],
        'email' => $user['email']
    ]
]);
?>
