<?php
/**
 * API Endpoint: Pembayaran
 * CRUD lengkap dengan prepared statements
 */
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM PEMBAYARAN WHERE id_pembayaran = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            jsonResponse($row ?: ['error' => 'Data tidak ditemukan'], $row ? 200 : 404);
        }
        $result = $conn->query("SELECT * FROM PEMBAYARAN");
        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;
        jsonResponse($rows);
        break;

    case 'POST':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("INSERT INTO PEMBAYARAN (id_pembayaran, tgl_bayar, jumlah_pembayaran, metode, id_transaksi, id_mobil) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isdsii', $d['id_pembayaran'], $d['tgl_bayar'], $d['jumlah_pembayaran'], $d['metode'], $d['id_transaksi'], $d['id_mobil']);
        if ($stmt->execute()) jsonResponse(['success' => true]);
        jsonResponse(['error' => $stmt->error], 400);
        break;

    case 'PUT':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("UPDATE PEMBAYARAN SET tgl_bayar=?, jumlah_pembayaran=?, metode=?, id_transaksi=?, id_mobil=? WHERE id_pembayaran=?");
        $stmt->bind_param('sdsiii', $d['tgl_bayar'], $d['jumlah_pembayaran'], $d['metode'], $d['id_transaksi'], $d['id_mobil'], $d['id_pembayaran']);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    case 'DELETE':
        requireAuth();
        $d = getJsonInput();
        $id = $d['id_pembayaran'] ?? $_GET['id'] ?? null;
        $stmt = $conn->prepare("DELETE FROM PEMBAYARAN WHERE id_pembayaran=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>
