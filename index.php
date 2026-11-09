<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Praktikum 7 - PHP</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f4f4f4;
        }

        h1 {
            text-align: center;
        }

        section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        input, button {
            padding: 8px;
            margin: 5px 0;
        }

        input {
            display: block;
        }

        button {
            cursor: pointer;
        }

        .hasil {
            background: #e8f5e9;
            padding: 10px;
            margin-top: 15px;
        }

        .error {
            background: #ffebee;
            color: red;
            padding: 10px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<h1>Praktikum 7 - Pemrograman Web PHP</h1>

<?php
function aman($data) {
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
}

function hitungDiskon($harga, $persen) {
    return $harga - ($harga * $persen / 100);
}

function hitungLuasPersegiPanjang($panjang, $lebar) {
    return $panjang * $lebar;
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

$nama = "Siti";
$tugas = 85;
$uts = 78;
$uas = 90;

$nilaiAkhir = ($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40);

if ($nilaiAkhir >= 85) {
    $predikat = "A";
} elseif ($nilaiAkhir >= 75) {
    $predikat = "B";
} elseif ($nilaiAkhir >= 60) {
    $predikat = "C";
} else {
    $predikat = "D";
}

$daftarNama = ["Alya", "Bima", "Citra", "Doni", "Eka", "Fajar", "Gita"];

$hasilGET = "";
$errorGET = "";

$hasilPOST = "";
$errorPOST = "";

$hasilBarang = "";
$errorBarang = "";

$hasilMahasiswa = "";
$errorMahasiswa = "";

$hasilRata = "";
$errorRata = "";

$hasilCari = "";
$errorCari = "";

$hasilNIM = "";
$errorNIM = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (isset($_GET["aksi"]) && $_GET["aksi"] == "get") {
        $kata = trim($_GET["kata"] ?? "");

        if ($kata == "") {
            $errorGET = "Masukkan kata kunci.";
        } else {
            $hasilGET = "Kata kunci yang dimasukkan: " . aman($kata);
        }
    }

    if (isset($_GET["aksi"]) && $_GET["aksi"] == "cari") {
        $cariNama = trim($_GET["nama"] ?? "");

        if ($cariNama == "") {
            $errorCari = "Masukkan nama yang ingin dicari.";
        } elseif (in_array($cariNama, $daftarNama)) {
            $hasilCari = "Nama " . aman($cariNama) . " ditemukan.";
        } else {
            $hasilCari = "Nama " . aman($cariNama) . " tidak ditemukan.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aksi = $_POST["aksi"] ?? "";

    if ($aksi == "post") {
        $namaPOST = trim($_POST["nama"] ?? "");
        $prodiPOST = trim($_POST["prodi"] ?? "");

        if ($namaPOST == "" || $prodiPOST == "") {
            $errorPOST = "Nama dan program studi wajib diisi.";
        } else {
            $hasilPOST = "Nama: " . aman($namaPOST) .
                         "<br>Program Studi: " . aman($prodiPOST);
        }
    }

    if ($aksi == "barang") {
        $barang = trim($_POST["barang"] ?? "");
        $harga = $_POST["harga"] ?? "";
        $jumlah = $_POST["jumlah"] ?? "";

        if ($barang == "") {
            $errorBarang = "Nama barang wajib diisi.";
        } elseif (!is_numeric($harga) || $harga < 0) {
            $errorBarang = "Harga harus berupa angka dan tidak boleh negatif.";
        } elseif (filter_var($jumlah, FILTER_VALIDATE_INT) === false || $jumlah < 1) {
            $errorBarang = "Jumlah harus berupa bilangan bulat minimal 1.";
        } else {
            $subtotal = $harga * $jumlah;

            $hasilBarang = "Nama Barang: " . aman($barang) .
                           "<br>Harga: " . formatRupiah($harga) .
                           "<br>Jumlah: " . aman($jumlah) .
                           "<br>Subtotal: " . formatRupiah($subtotal);
        }
    }

    if ($aksi == "mahasiswa") {
        $namaMhs = trim($_POST["nama"] ?? "");
        $nim = trim($_POST["nim"] ?? "");
        $prodi = trim($_POST["prodi"] ?? "");
        $semester = $_POST["semester"] ?? "";
        $email = trim($_POST["email"] ?? "");

        if ($namaMhs == "" || $nim == "" || $prodi == "" || $email == "") {
            $errorMahasiswa = "Semua data mahasiswa wajib diisi.";
        } elseif (filter_var($semester, FILTER_VALIDATE_INT) === false ||
                  $semester < 1 || $semester > 14) {
            $errorMahasiswa = "Semester harus berupa angka dari 1 sampai 14.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMahasiswa = "Format email tidak valid.";
        } else {
            $hasilMahasiswa = "Data Mahasiswa Berhasil Dimasukkan" .
                              "<br>Nama: " . aman($namaMhs) .
                              "<br>NIM: " . aman($nim) .
                              "<br>Program Studi: " . aman($prodi) .
                              "<br>Semester: " . aman($semester) .
                              "<br>Email: " . aman($email);
        }
    }

    if ($aksi == "rata") {
        $nilai1 = $_POST["nilai1"] ?? "";
        $nilai2 = $_POST["nilai2"] ?? "";
        $nilai3 = $_POST["nilai3"] ?? "";

        if (!is_numeric($nilai1) || !is_numeric($nilai2) || !is_numeric($nilai3)) {
            $errorRata = "Semua nilai harus berupa angka.";
        } else {
            $rata = ($nilai1 + $nilai2 + $nilai3) / 3;
            $hasilRata = "Rata-rata nilai: " . number_format($rata, 2);
        }
    }

    if ($aksi == "nim") {
        $nimEks = trim($_POST["nim"] ?? "");

        if (!preg_match("/^[0-9]{9}$/", $nimEks)) {
            $errorNIM = "NIM harus terdiri dari 9 digit angka.";
        } else {
            $hasilNIM = "NIM valid: " . aman($nimEks);
        }
    }
}
?>

<section id="latihan1">
    <h2>Latihan 1 - Menjalankan PHP</h2>

    <?php
    echo "<p>PHP berhasil dijalankan.</p>";
    echo "<p>Waktu server: " . date("d-m-Y H:i:s") . "</p>";
    ?>
</section>

<section id="latihan2">
    <h2>Latihan 2 - Variabel, Operator, dan Percabangan</h2>

    <p>Nama: <?= aman($nama) ?></p>
    <p>Nilai Tugas: <?= $tugas ?></p>
    <p>Nilai UTS: <?= $uts ?></p>
    <p>Nilai UAS: <?= $uas ?></p>
    <p>Nilai Akhir: <?= number_format($nilaiAkhir, 2) ?></p>
    <p>Predikat: <?= $predikat ?></p>
</section>

<section id="latihan3">
    <h2>Latihan 3 - Array dan Foreach</h2>

    <p>Daftar Nama Mahasiswa:</p>

    <ul>
        <?php foreach ($daftarNama as $namaMahasiswa): ?>
            <li><?= aman($namaMahasiswa) ?></li>
        <?php endforeach; ?>
    </ul>
</section>

<section id="latihan4">
    <h2>Latihan 4 - Function</h2>

    <?php
    $hargaAwal = 150000;
    $hargaAkhir = hitungDiskon($hargaAwal, 10);
    ?>

    <p>Harga Awal: <?= formatRupiah($hargaAwal) ?></p>
    <p>Diskon: 10%</p>
    <p>Harga Setelah Diskon: <?= formatRupiah($hargaAkhir) ?></p>

    <p>Luas Persegi Panjang: <?= hitungLuasPersegiPanjang(10, 5) ?> cm²</p>
</section>

<section id="latihan5">
    <h2>Latihan 5 - Form GET</h2>

    <form method="GET" action="index.php#latihan5">
        <input type="hidden" name="aksi" value="get">

        <label>Masukkan Kata Kunci:</label>
        <input type="text" name="kata">

        <button type="submit">Kirim GET</button>
    </form>

    <?php if ($hasilGET != ""): ?>
        <div class="hasil">
            <strong>Hasil:</strong><br>
            <?= $hasilGET ?>
        </div>
    <?php endif; ?>

    <?php if ($errorGET != ""): ?>
        <div class="error"><?= aman($errorGET) ?></div>
    <?php endif; ?>
</section>

<section id="latihan6">
    <h2>Latihan 6 - Form POST</h2>

    <form method="POST" action="index.php#latihan6">
        <input type="hidden" name="aksi" value="post">

        <label>Nama:</label>
        <input type="text" name="nama">

        <label>Program Studi:</label>
        <input type="text" name="prodi">

        <button type="submit">Kirim POST</button>
    </form>

    <?php if ($hasilPOST != ""): ?>
        <div class="hasil">
            <strong>Hasil:</strong><br>
            <?= $hasilPOST ?>
        </div>
    <?php endif; ?>

    <?php if ($errorPOST != ""): ?>
        <div class="error"><?= aman($errorPOST) ?></div>
    <?php endif; ?>
</section>

<section id="latihan7">
    <h2>Latihan 7 - Pengolahan Data Barang</h2>

    <form method="POST" action="index.php#latihan7">
        <input type="hidden" name="aksi" value="barang">

        <label>Nama Barang:</label>
        <input type="text" name="barang">

        <label>Harga:</label>
        <input type="number" name="harga">

        <label>Jumlah:</label>
        <input type="number" name="jumlah">

        <button type="submit">Hitung Subtotal</button>
    </form>

    <?php if ($hasilBarang != ""): ?>
        <div class="hasil">
            <strong>Hasil:</strong><br>
            <?= $hasilBarang ?>
        </div>
    <?php endif; ?>

    <?php if ($errorBarang != ""): ?>
        <div class="error"><?= aman($errorBarang) ?></div>
    <?php endif; ?>
</section>

<section id="mini">
    <h2>Mini Project - Formulir Data Mahasiswa</h2>

    <form method="POST" action="index.php#mini">
        <input type="hidden" name="aksi" value="mahasiswa">

        <label>Nama:</label>
        <input type="text" name="nama">

        <label>NIM:</label>
        <input type="text" name="nim">

        <label>Program Studi:</label>
        <input type="text" name="prodi">

        <label>Semester:</label>
        <input type="number" name="semester" min="1" max="14">

        <label>Email:</label>
        <input type="email" name="email">

        <button type="submit">Simpan Data</button>
    </form>

    <?php if ($hasilMahasiswa != ""): ?>
        <div class="hasil">
            <strong>Hasil:</strong><br>
            <?= $hasilMahasiswa ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMahasiswa != ""): ?>
        <div class="error"><?= aman($errorMahasiswa) ?></div>
    <?php endif; ?>
</section>

<section id="eksperimen1">
    <h2>Eksperimen 1 - Rata-rata Tiga Nilai</h2>

    <form method="POST" action="index.php#eksperimen1">
        <input type="hidden" name="aksi" value="rata">

        <label>Nilai 1:</label>
        <input type="number" name="nilai1">

        <label>Nilai 2:</label>
        <input type="number" name="nilai2">

        <label>Nilai 3:</label>
        <input type="number" name="nilai3">

        <button type="submit">Hitung Rata-rata</button>
    </form>

    <?php if ($hasilRata != ""): ?>
        <div class="hasil"><?= $hasilRata ?></div>
    <?php endif; ?>

    <?php if ($errorRata != ""): ?>
        <div class="error"><?= aman($errorRata) ?></div>
    <?php endif; ?>
</section>

<section id="eksperimen2">
    <h2>Eksperimen 2 - Pencarian Nama dengan GET</h2>

    <p>Nama tersedia: Alya, Bima, Citra, Doni, Eka, Fajar, Gita.</p>

    <form method="GET" action="index.php#eksperimen2">
        <input type="hidden" name="aksi" value="cari">

        <label>Nama yang Dicari:</label>
        <input type="text" name="nama">

        <button type="submit">Cari Nama</button>
    </form>

    <?php if ($hasilCari != ""): ?>
        <div class="hasil"><?= $hasilCari ?></div>
    <?php endif; ?>

    <?php if ($errorCari != ""): ?>
        <div class="error"><?= aman($errorCari) ?></div>
    <?php endif; ?>
</section>

<section id="eksperimen3">
    <h2>Eksperimen 3 - Format Rupiah</h2>

    <?php $uang = 250000; ?>

    <p>Angka: <?= $uang ?></p>
    <p>Format Rupiah: <?= formatRupiah($uang) ?></p>
</section>

<section id="eksperimen4">
    <h2>Eksperimen 4 - Validasi NIM</h2>

    <form method="POST" action="index.php#eksperimen4">
        <input type="hidden" name="aksi" value="nim">

        <label>Masukkan NIM (9 digit):</label>
        <input type="text" name="nim">

        <button type="submit">Validasi NIM</button>
    </form>

    <?php if ($hasilNIM != ""): ?>
        <div class="hasil"><?= $hasilNIM ?></div>
    <?php endif; ?>

    <?php if ($errorNIM != ""): ?>
        <div class="error"><?= aman($errorNIM) ?></div>
    <?php endif; ?>
</section>

<section id="eksperimen5">
    <h2>Eksperimen 5 - Tombol Reset</h2>

    <form>
        <label>Nama:</label>
        <input type="text" name="nama">

        <label>Program Studi:</label>
        <input type="text" name="prodi">

        <button type="reset">Reset</button>
    </form>

    <p>
        Tombol reset menghapus kembali input pada form di browser.
        Tombol reset tidak mengirim data ke server.
    </p>
</section>

</body>
</html>