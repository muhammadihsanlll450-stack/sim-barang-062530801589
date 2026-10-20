const form = document.getElementById("form-barang-input");
const inputKode = document.getElementById("kode");
const inputNama = document.getElementById("nama");
const inputKategori = document.getElementById("kategori");
const inputJumlah = document.getElementById("jumlah");
const inputKondisi = document.getElementById("kondisi");
const inputCari = document.getElementById("cari");
const tbody = document.querySelector("#tabel-barang tbody");
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

  // BARU: tambahkan baris ke tabel
  const tr = document.createElement("tr");
  [kode, inputNama.value, inputKategori.value, jumlah, inputKondisi.value]
    .forEach(function (nilai) {
      const td = document.createElement("td");
      td.textContent = nilai;
      tr.appendChild(td);
    });
  tbody.appendChild(tr);

  tampilToast("Data barang berhasil ditambahkan!", true);
  form.reset();
});

// ===== Filter tabel secara real-time =====
inputCari.addEventListener("input", function () {
  const kataKunci = inputCari.value.toLowerCase();

  // BARU: daftar baris diambil ulang tiap kali, supaya baris baru ikut terfilter
  tbody.querySelectorAll("tr").forEach(function (tr) {
    const teksBaris = tr.textContent.toLowerCase();
    tr.style.display = teksBaris.includes(kataKunci) ? "" : "none";
  });
});

// ===== Toast notifikasi =====
let timerToast;

function tampilToast(pesan, sukses) {
  toast.textContent = pesan;
  toast.classList.toggle("error", !sukses);
  toast.hidden = false;

  clearTimeout(timerToast);
  timerToast = setTimeout(function () {
    toast.hidden = true;
  }, 3000);
}