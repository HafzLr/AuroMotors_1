/* ============================================
   JS GLOBAL - SHOWROOM MOBIL
   ============================================ */

// Nomor WhatsApp showroom (ganti dengan nomor asli)
const WA_NUMBER = '6282298028685';
const PLACEHOLDER_IMAGE = 'https://placehold.co/400x250/111111/FFD700';

// Format harga ke Rupiah
function formatRupiah(angka) {
  return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

// Generate link WhatsApp dengan pesan otomatis
function openWhatsApp(mobil) {
  const pesan = `Halo, saya tertarik dengan mobil berikut:\n🚗 ${mobil.merk} ${mobil.tipe} ${mobil.tahun}\n🎨 Warna: ${mobil.warna}\n💰 Harga: ${formatRupiah(mobil.harga)}\nApakah masih tersedia?`;
  window.open(`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(pesan)}`, '_blank');
}

// Toggle navbar mobile
function toggleNav() {
  document.querySelector('.nav-links')?.classList.toggle('open');
}

// Render kartu mobil reusable
function renderCarCard(m) {
  const badge = m.kondisi === 'Baru'
    ? '<span class="car-badge badge-baru">Baru</span>'
    : '<span class="car-badge badge-bekas">Bekas</span>';
  return `
    <div class="car-card">
      <img src="${m.gambar || PLACEHOLDER_IMAGE}" alt="${m.merk} ${m.tipe}" class="car-image" loading="lazy">
      <div class="car-body">
        ${badge}
        <h3 class="car-title">${m.merk} ${m.tipe}</h3>
        <div class="car-meta">${m.tahun} • ${m.warna}</div>
        <div class="car-price">${formatRupiah(m.harga)}</div>
        <div class="car-actions">
          <a href="detail.html?id=${m.id_mobil}" class="btn btn-outline btn-sm">Detail</a>
          <button class="btn btn-wa btn-sm" onclick='openWhatsApp(${JSON.stringify(m).replace(/'/g, "&apos;")})'>WhatsApp</button>
        </div>
      </div>
    </div>
  `;
}

// Cek auth status
function isLoggedIn() {
  return !!localStorage.getItem('showroom_token');
}

function logout() {
  localStorage.removeItem('showroom_token');
  localStorage.removeItem('showroom_user');
  window.location.href = '../login.html';
}
