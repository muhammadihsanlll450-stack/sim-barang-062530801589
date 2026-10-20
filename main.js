const form = document.getElementById("form-barang-input");
const inputKode = document.getElementById("kode");
const inputJumlah = document.getElementById("jumlah");
const inputCari = document.getElementById("cari");
const baris = document.querySelectorAll("#tabel-barang tbody tr");
const toast = document.getElementById("toast");

// ===== Validasi form saat submit =====
form.addEventListener("submit", function (e) {
  e.preventDefault(); // cegah form terkirim sebelum divalidasi

  const kode = inputKode.value.trim();
  const jumlah = Number(inputJumlah.value);

  // Kode harus diawali "BRG-" lalu satu angka atau lebih
  const polaKode = /^BRG-\d+$/;
  if (!polaKode.test(kode)) {
    tampilToast("Kode harus diawali BRG- lalu angka, contoh: BRG-001", false);
    inputKode.focus();
    return;
  }

  // Jumlah harus angka positif (> 0)
  if (Number.isNaN(jumlah) || jumlah <= 0) {
    tampilToast("Jumlah harus berupa angka lebih dari 0", false);
    inputJumlah.focus();
    return;
  }

  // Lolos validasi
  tampilToast("Data barang berhasil divalidasi!", true);
  form.reset();
});

// ===== Filter tabel secara real-time =====
inputCari.addEventListener("input", function () {
  const kataKunci = inputCari.value.toLowerCase();

  baris.forEach(function (tr) {
    const teksBaris = tr.textContent.toLowerCase();
    // Tampilkan baris kalau mengandung kata kunci, sembunyikan kalau tidak
    tr.style.display = teksBaris.includes(kataKunci) ? "" : "none";
  });
});

// ===== Toast notifikasi =====
let timerToast;

function tampilToast(pesan, sukses) {
  toast.textContent = pesan;
  toast.classList.toggle("error", !sukses);
  toast.hidden = false;

  clearTimeout(timerToast); // reset timer kalau toast dipanggil berulang
  timerToast = setTimeout(function () {
    toast.hidden = true;
  }, 3000);
}