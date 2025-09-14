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
?>
