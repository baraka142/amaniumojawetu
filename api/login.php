<?php
header('Content-Type: application/json');

// Replace with your actual Neon DB credentials
$host = 'your-neon-host';
$db   = 'neondb';
$user = 'your-db-user';
$pass = 'your-db-password';
$port = 5432;

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if (!$conn) {
  echo json_encode(['message' => 'Database connection failed']);
  exit;
}

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

$query = "SELECT * FROM public.userdata WHERE username = $1 AND email = $2";
$result = pg_query_params($conn, $query, [$username, $email]);

if (pg_num_rows($result) > 0) {
  echo json_encode(['message' => 'Login successful']);
} else {
  echo json_encode(['message' => 'Invalid credentials']);
}
?>
