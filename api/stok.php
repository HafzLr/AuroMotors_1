<?php
/**
 * API Endpoint: Stok
 * CRUD lengkap dengan prepared statements
 */
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM STOK WHERE id_stok = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            jsonResponse($row ?: ['error' => 'Data tidak ditemukan'], $row ? 200 : 404);
        }
        $result = $conn->query("SELECT * FROM STOK");
        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;
        jsonResponse($rows);
        break;

    case 'POST':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("INSERT INTO STOK (id_stok, jumlah, tanggal_masuk) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $d['id_stok'], $d['jumlah'], $d['tanggal_masuk']);
        if ($stmt->execute()) jsonResponse(['success' => true]);
        jsonResponse(['error' => $stmt->error], 400);
        break;

    case 'PUT':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("UPDATE STOK SET jumlah=?, tanggal_masuk=? WHERE id_stok=?");
        $stmt->bind_param('isi', $d['jumlah'], $d['tanggal_masuk'], $d['id_stok']);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    case 'DELETE':
        requireAuth();
        $d = getJsonInput();
        $id = $d['id_stok'] ?? $_GET['id'] ?? null;
        $stmt = $conn->prepare("DELETE FROM STOK WHERE id_stok=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>
