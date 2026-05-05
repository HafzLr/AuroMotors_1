/* ============================================
   API CLIENT - Fetch ke PHP Backend
   ============================================ */

// GANTI dengan URL server PHP Anda
const API_BASE = 'https://api.yourdomain.com';
// Contoh untuk lokal: 'http://localhost/showroom/api'

async function apiGet(endpoint, params = {}) {
  const url = new URL(`${API_BASE}/${endpoint}`);
  Object.entries(params).forEach(([k, v]) => v != null && url.searchParams.append(k, v));
  try {
    const res = await fetch(url, { headers: authHeaders() });
    return await res.json();
  } catch (e) {
    console.error('API GET error:', e);
    return { error: 'Gagal terhubung ke server' };
  }
}

async function apiPost(endpoint, data) {
  return apiSend(endpoint, 'POST', data);
}
async function apiPut(endpoint, data) {
  return apiSend(endpoint, 'PUT', data);
}
async function apiDelete(endpoint, data) {
  return apiSend(endpoint, 'DELETE', data);
}

async function apiSend(endpoint, method, data) {
  try {
    const res = await fetch(`${API_BASE}/${endpoint}`, {
      method,
      headers: { 'Content-Type': 'application/json', ...authHeaders() },
      body: JSON.stringify(data)
    });
    return await res.json();
  } catch (e) {
    console.error('API error:', e);
    return { error: 'Gagal terhubung ke server' };
  }
}

function authHeaders() {
  const token = localStorage.getItem('showroom_token');
  return token ? { 'Authorization': 'Bearer ' + token } : {};
}

const STORAGE_MOBIL_KEY = 'showroom_mobil_data';

function loadStoredMobil() {
  try {
    const raw = localStorage.getItem(STORAGE_MOBIL_KEY);
    const data = raw ? JSON.parse(raw) : null;
    return Array.isArray(data) ? data : null;
  } catch (e) {
    console.warn('Gagal membaca data local mobil:', e);
    return null;
  }
}

function saveStoredMobil(data) {
  try {
    localStorage.setItem(STORAGE_MOBIL_KEY, JSON.stringify(data));
  } catch (e) {
    console.warn('Gagal menyimpan data local mobil:', e);
  }
}

// Data dummy fallback ketika API belum tersedia (untuk demo statis di GitHub Pages)
const DUMMY_MOBIL = [
  { id_mobil: 1, merk: 'Toyota', tipe: 'Innova Zenix', warna: 'Hitam', harga: 450000000, tahun: 2024, kondisi: 'Baru', garansi: 'Garansi Resmi 3 Tahun', nama_supplier: 'PT Auto Prima', stok: 5 },
  { id_mobil: 2, merk: 'Honda', tipe: 'Civic Type R', warna: 'Putih', harga: 1300000000, tahun: 2024, kondisi: 'Baru', garansi: 'Garansi Resmi 3 Tahun', nama_supplier: 'PT Auto Prima', stok: 2 },
  { id_mobil: 3, merk: 'BMW', tipe: 'M3 Competition', warna: 'Gold', harga: 2500000000, tahun: 2023, kondisi: 'Baru', garansi: 'Garansi Resmi 5 Tahun', nama_supplier: 'CV Mobil Jaya', stok: 1 },
  { id_mobil: 4, merk: 'Mercedes-Benz', tipe: 'C-Class', warna: 'Hitam', harga: 1100000000, tahun: 2023, kondisi: 'Bekas', garansi: 'Garansi 1 Tahun Mesin', nama_supplier: 'CV Mobil Jaya', stok: 3 },
  { id_mobil: 5, merk: 'Toyota', tipe: 'Avanza', warna: 'Silver', harga: 240000000, tahun: 2022, kondisi: 'Bekas', garansi: 'Garansi 6 Bulan', nama_supplier: 'PT Auto Prima', stok: 4 },
  { id_mobil: 6, merk: 'Honda', tipe: 'Brio RS', warna: 'Merah', harga: 195000000, tahun: 2023, kondisi: 'Baru', garansi: 'Garansi Resmi 3 Tahun', nama_supplier: 'PT Auto Prima', stok: 6 }
];

// Fetch mobil dengan fallback
async function getMobil(params = {}) {
  const res = await apiGet('mobil.php', params);
  if (res.error || !Array.isArray(res)) {
    const stored = loadStoredMobil();
    if (stored) return stored;
    console.warn('Menggunakan data dummy karena API tidak tersedia');
    return DUMMY_MOBIL;
  }
  return res;
}

async function getMobilById(id) {
  const res = await apiGet('mobil.php', { id });
  if (res.error || !res.id_mobil) {
    const stored = loadStoredMobil();
    if (stored) {
      const found = stored.find(m => m.id_mobil == id);
      if (found) return found;
    }
    return DUMMY_MOBIL.find(m => m.id_mobil == id);
  }
  return res;
}
