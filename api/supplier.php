<?php
/**
 * API Endpoint: Supplier
 * CRUD lengkap dengan prepared statements
 */
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM SUPPLIER WHERE id_supplier = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            jsonResponse($row ?: ['error' => 'Data tidak ditemukan'], $row ? 200 : 404);
        }
        $result = $conn->query("SELECT * FROM SUPPLIER");
        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;
        jsonResponse($rows);
        break;

    case 'POST':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("INSERT INTO SUPPLIER (id_supplier, nama_supplier, jalan, kota, kode_pos) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('issss', $d['id_supplier'], $d['nama_supplier'], $d['jalan'], $d['kota'], $d['kode_pos']);
        if ($stmt->execute()) jsonResponse(['success' => true]);
        jsonResponse(['error' => $stmt->error], 400);
        break;

    case 'PUT':
        requireAuth();
        $d = getJsonInput();
        $stmt = $conn->prepare("UPDATE SUPPLIER SET nama_supplier=?, jalan=?, kota=?, kode_pos=? WHERE id_supplier=?");
        $stmt->bind_param('ssssi', $d['nama_supplier'], $d['jalan'], $d['kota'], $d['kode_pos'], $d['id_supplier']);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    case 'DELETE':
        requireAuth();
        $d = getJsonInput();
        $id = $d['id_supplier'] ?? $_GET['id'] ?? null;
        $stmt = $conn->prepare("DELETE FROM SUPPLIER WHERE id_supplier=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>
