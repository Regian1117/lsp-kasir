<?php
/**
 * Upload foto dengan nama unik: [randomInt]_[namaInput].[ekstensi]
 * @param array $file -> $_FILES['foto']
 * @param string $targetDir -> folder tujuan upload (contoh: 'uploads/menu/')
 * @param string $namaInput -> nama dari form (misal $_POST['nama_menu'])
 * @param int $maxSize -> maksimal size file dalam byte (default 2MB)
 * @param array $allowedExt -> ekstensi yang diperbolehkan
 * @return string|false -> nama file baru jika sukses, false jika gagal
 */
function upload_foto($file, $targetDir, $namaInput, $maxSize = 2097152, $allowedExt = ['jpg','jpeg','png']){
    if($file['error'] !== 0){
        return false; // ada error upload
    }

    $namaFile = $file['name'];
    $tmpName  = $file['tmp_name'];
    $size     = $file['size'];

    // validasi ekstensi
    $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    if(!in_array($ekstensi, $allowedExt)){
        return false;
    }

    // validasi ukuran
    if($size > $maxSize){
        return false;
    }

    // bersihkan nama input: spasi jadi _, hilangkan karakter ilegal
    $namaInput = preg_replace("/[^a-zA-Z0-9\s_-]/", "", $namaInput); // hapus karakter ilegal
    $namaInput = str_replace(' ', '_', $namaInput); // ganti spasi dengan _

    // generate nama baru: randomInt + namaInput + ekstensi
    $randomInt = rand(100, 999);
    $namaBaru = $randomInt . '_' . $namaInput . '.' . $ekstensi;

    // pastikan folder ada
    if(!is_dir($targetDir)){
        mkdir($targetDir, 0755, true);
    }

    // pindahkan file ke folder tujuan
    if(move_uploaded_file($tmpName, $targetDir . $namaBaru)){
        return $targetDir . $namaBaru;
    } else {
        return false;
    }
}

function generateKodeTransaksi($conn) {
    // prefix + tanggal
    $prefix = "TRX" . date("Ymd");

    // cek transaksi terakhir hari ini
    $sql = "SELECT kode_transaksi 
            FROM transaksi 
            WHERE kode_transaksi LIKE '$prefix%'
            ORDER BY kode_transaksi DESC 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $lastNumber = 0;

    if ($row = mysqli_fetch_assoc($result)) {
        // ambil nomor urut terakhir
        $lastCode = $row['kode_transaksi'];
        $lastNumber = (int)substr($lastCode, -3); // ambil 3 digit terakhir
    }

    // tambah 1 nomor urut
    $newNumber = str_pad($lastNumber + 1, 3, "0", STR_PAD_LEFT);

    // hasil akhir
    return $prefix . "-" . $newNumber;
}

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>
