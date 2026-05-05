<?php
/**
 * API Endpoint: Mobil
 * Mendukung GET (list/detail), POST (tambah), PUT (edit), DELETE (hapus)
 * Semua query menggunakan PREPARED STATEMENTS untuk mencegah SQL Injection
 */
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        // GET ?id=1 -> detail satu mobil
        // GET (tanpa id) -> list semua mobil
        $id = $_GET['id'] ?? null;

        $sql = "SELECT m.*,
                  CASE WHEN mb.id_mobil IS NOT NULL THEN 'Baru' ELSE 'Bekas' END as kondisi,
                  COALESCE(mb.garansi, mk.garansi) as garansi,
                  s.nama_supplier,
                  (SELECT SUM(jumlah) FROM STOK) as stok
                FROM MOBIL m
                LEFT JOIN MOBIL_BARU mb ON m.id_mobil = mb.id_mobil
                LEFT JOIN MOBIL_BEKAS mk ON m.id_mobil = mk.id_mobil
                LEFT JOIN SUPPLIER s ON m.id_supplier = s.id_supplier";

        if ($id) {
            $sql .= " WHERE m.id_mobil = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            jsonResponse($result ?: ['error' => 'Mobil tidak ditemukan'], $result ? 200 : 404);
        } else {
            $result = $conn->query($sql);
            $rows = [];
            while ($row = $result->fetch_assoc()) $rows[] = $row;
            jsonResponse($rows);
        }
        break;

    case 'POST':
        requireAuth();
        $d = getJsonInput();
        // Tambahkan kolom 'gambar' (URL atau data base64) saat insert
        $gambar = $d['gambar'] ?? null;
        $stmt = $conn->prepare("INSERT INTO MOBIL (id_mobil, merk, tipe, warna, harga, tahun, id_supplier, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssdiis',
            $d['id_mobil'], $d['merk'], $d['tipe'], $d['warna'],
            $d['harga'], $d['tahun'], $d['id_supplier'], $gambar
        );
        if ($stmt->execute()) {
            // Insert kondisi (Baru/Bekas)
            if (($d['kondisi'] ?? '') === 'Baru') {
                $s2 = $conn->prepare("INSERT INTO MOBIL_BARU (id_mobil, garansi) VALUES (?, ?)");
                $s2->bind_param('is', $d['id_mobil'], $d['garansi']);
                $s2->execute();
            } elseif (($d['kondisi'] ?? '') === 'Bekas') {
                $s2 = $conn->prepare("INSERT INTO MOBIL_BEKAS (id_mobil, garansi) VALUES (?, ?)");
                $s2->bind_param('is', $d['id_mobil'], $d['garansi']);
                $s2->execute();
            }
            jsonResponse(['success' => true, 'id' => $d['id_mobil']]);
        }
        jsonResponse(['error' => $stmt->error], 400);
        break;

    case 'PUT':
        requireAuth();
        $d = getJsonInput();
        // Update termasuk kolom 'gambar'
        $gambar = $d['gambar'] ?? null;
        $stmt = $conn->prepare("UPDATE MOBIL SET merk=?, tipe=?, warna=?, harga=?, tahun=?, id_supplier=?, gambar=? WHERE id_mobil=?");
        $stmt->bind_param('sssdiisi',
            $d['merk'], $d['tipe'], $d['warna'], $d['harga'],
            $d['tahun'], $d['id_supplier'], $gambar, $d['id_mobil']
        );
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    case 'DELETE':
        requireAuth();
        $d = getJsonInput();
        $id = $d['id_mobil'] ?? $_GET['id'] ?? null;
        $conn->prepare("DELETE FROM MOBIL_BARU WHERE id_mobil=?")->execute([$id]);
        $conn->prepare("DELETE FROM MOBIL_BEKAS WHERE id_mobil=?")->execute([$id]);
        $stmt = $conn->prepare("DELETE FROM MOBIL WHERE id_mobil=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        jsonResponse(['success' => true]);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>
