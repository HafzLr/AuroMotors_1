<?php
/**
 * API Endpoint: Sales
 * CRUD lengkap dengan prepared statements
 */
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM SALES WHERE id_pegawai = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            jsonResponse($row ?: ['error' => 'Data tidak ditemukan'], $row ? 200 : 404);
        }
        $result = $conn->query("SELECT * FROM SALES");
        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;
        jsonResponse($rows);
        break;

    case 'POST':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("INSERT INTO SALES (id_pegawai, target_penjualan, komisi) VALUES (?, ?, ?)");
        $stmt->bind_param('idd', $d['id_pegawai'], $d['target_penjualan'], $d['komisi']);
        if ($stmt->execute()) jsonResponse(['success' => true]);
        jsonResponse(['error' => $stmt->error], 400);
        break;

    case 'PUT':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("UPDATE SALES SET target_penjualan=?, komisi=? WHERE id_pegawai=?");
        $stmt->bind_param('ddi', $d['target_penjualan'], $d['komisi'], $d['id_pegawai']);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    case 'DELETE':
        requireAuth();
        $d = getJsonInput();
        $id = $d['id_pegawai'] ?? $_GET['id'] ?? null;
        $stmt = $conn->prepare("DELETE FROM SALES WHERE id_pegawai=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>
