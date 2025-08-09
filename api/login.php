<?php
header('Content-Type: application/json');

// Use environment variable if set, otherwise fallback to direct string
$connectionString = getenv('DATABASE_URL') ?: "host=ep-crimson-snow-admapr74-pooler.c-2.us-east-1.aws.neon.tech
  dbname=neondb
  user=neondb_owner
  password=npg_6kw8UVPQpbKH
  sslmode=require
  options='--client_encoding=UTF8'";

// Connect to Neon PostgreSQL
$conn = pg_connect($connectionString);

if (!$conn) {
  echo json_encode(['message' => '❌ Database connection failed']);
  exit;
}

// Get POST data safely
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validate input
if (empty($username) || empty($email)) {
  echo json_encode(['message' => '⚠️ Username and email are required']);
  exit;
}

// Query the database
$query = "SELECT * FROM public.userdata WHERE username = $1 AND email = $2";
$result = pg_query_params($conn, $query, [$username, $email]);

if (!$result) {
  echo json_encode(['message' => '❌ Query failed']);
  exit;
}

// Check if user exists
if (pg_num_rows($result) > 0) {
  echo json_encode(['message' => '✅ Login successful']);
} else {
  echo json_encode(['message' => '❌ Invalid credentials']);
}
?>
