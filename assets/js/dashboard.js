/* ============================================
   JS DASHBOARD - Logika halaman admin
   ============================================ */

// Cek auth, redirect bila belum login
if (!isLoggedIn()) {
  window.location.href = '../login.html';
}

// Helper untuk render tabel generik
function renderTable(targetId, data, columns, actions) {
  const tbody = document.querySelector(`#${targetId} tbody`);
  if (!tbody) return;
  if (!data || data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="${columns.length + (actions ? 1 : 0)}" style="text-align:center;padding:2rem;color:var(--text-muted)">Belum ada data</td></tr>`;
    return;
  }
  tbody.innerHTML = data.map(row => {
    const tds = columns.map(c => `<td>${typeof c.render === 'function' ? c.render(row) : (row[c.key] ?? '-')}</td>`).join('');
    const actionsTd = actions ? `<td><div class="action-btns">${actions(row)}</div></td>` : '';
    return `<tr>${tds}${actionsTd}</tr>`;
  }).join('');
}

function openModal(id) { document.getElementById(id)?.classList.add('active'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('active'); }

// Tutup modal saat klik overlay
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('active');
  }
});
