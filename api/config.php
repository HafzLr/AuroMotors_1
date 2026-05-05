<?php
/**
 * Konfigurasi koneksi database MySQL
 * Dipakai oleh semua endpoint API
 */

$host = 'localhost';
$db   = 'db_showroom';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Koneksi database gagal']));
}
$conn->set_charset('utf8mb4');

// CORS & Header JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Helper untuk membaca body JSON
function getJsonInput() {
    $input = file_get_contents('php://input');
    return json_decode($input, true) ?? [];
}

// Helper response
function jsonResponse($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Helper cek auth (sederhana, sesuai kebutuhan bisa diperluas dengan JWT)
function requireAuth() {
    $headers = getallheaders();
    $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    if (!preg_match('/Bearer\s+(.+)/', $auth, $m)) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }
    return $m[1];
}
?>
