<?php
/**
 * API Endpoint: Autentikasi (login/logout)
 * Query ke ADMIN JOIN PEGAWAI untuk validasi user
 */
require_once 'config.php';

$d = getJsonInput();
$action = $d['action'] ?? 'login';

if ($action === 'login') {
    $username = $d['username'] ?? '';
    $password = $d['password'] ?? '';

    // Prepared statement: cegah SQL injection
    $stmt = $conn->prepare("
        SELECT a.id_pegawai, a.username, a.password, p.nama, p.no_hp
        FROM ADMIN a
        JOIN PEGAWAI p ON a.id_pegawai = p.id_pegawai
        WHERE a.username = ?
    ");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(['error' => 'Username atau password salah'], 401);
    }

    // Generate token sederhana (di production gunakan JWT)
    $token = bin2hex(random_bytes(32));

    unset($user['password']);
    jsonResponse([
        'token' => $token,
        'user'  => $user
    ]);
}

if ($action === 'logout') {
    // Invalidasi token (jika menggunakan storage server-side)
    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Action tidak valid'], 400);
?>
