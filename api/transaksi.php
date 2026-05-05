<?php
/**
 * API Endpoint: Transaksi
 * CRUD lengkap dengan prepared statements
 */
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM TRANSAKSI WHERE id_transaksi = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            jsonResponse($row ?: ['error' => 'Data tidak ditemukan'], $row ? 200 : 404);
        }
        $result = $conn->query("SELECT * FROM TRANSAKSI");
        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;
        jsonResponse($rows);
        break;

    case 'POST':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("INSERT INTO TRANSAKSI (id_transaksi, tanggal, total_harga, id_pelanggan, id_pegawai) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('isdii', $d['id_transaksi'], $d['tanggal'], $d['total_harga'], $d['id_pelanggan'], $d['id_pegawai']);
        if ($stmt->execute()) jsonResponse(['success' => true]);
        jsonResponse(['error' => $stmt->error], 400);
        break;

    case 'PUT':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("UPDATE TRANSAKSI SET tanggal=?, total_harga=?, id_pelanggan=?, id_pegawai=? WHERE id_transaksi=?");
        $stmt->bind_param('sdiii', $d['tanggal'], $d['total_harga'], $d['id_pelanggan'], $d['id_pegawai'], $d['id_transaksi']);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    case 'DELETE':
        requireAuth();
        $d = getJsonInput();
        $id = $d['id_transaksi'] ?? $_GET['id'] ?? null;
        $stmt = $conn->prepare("DELETE FROM TRANSAKSI WHERE id_transaksi=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>
