<?php

include 'includes/functions.php';

$id = $_POST['id'];
$nama_menu = $_POST['nama_menu'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$foto = $_POST['foto'];

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $foto_lama = $_POST['foto_lama'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] != 4) { // error 4 = no file
        $foto_baru = upload_foto($_FILES['foto'], 'uploads/menu/', $nama_menu); //func dari includes/function
        if ($foto_baru) {
            // hapus foto lama jika ada
            if (file_exists('uploads/menu/' . $foto_lama)) {
                unlink('uploads/menu/' . $foto_lama);
            }
            $foto = $foto_baru;
        } else {
            $foto = $foto_lama; // gagal upload, tetap pakai lama
        }
    } else {
        $foto = $foto_lama; // tidak ganti foto
    }

    $stmt = $koneksi->prepare("update menu set nama_menu=?, harga=?, stok=?, foto=? where id=?");
    $stmt->bind_param('siisi', $nama_menu, $harga, $stok, $foto, $id);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php?page=menu');
    exit();
}

?>
<div class="w-fit">
    <form method="post" class="flex flex-col border gap-1">
        <div class="bg-[url(<?= $foto ?>)] bg-cover bg-center h-64 w-full flex items-end">
            <p class="bg-blue-500 p-3 text-white">Ganti Foto</p>
            <input type="file" name="foto" accept="image/*">
            <input type="hidden" name="foto_lama" value="<?= $foto ?>">
        </div>
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="flex gap-1">
            <label>Nama Menu: </label>
            <input type="text" name="nama_menu" value="<?= $nama_menu ?>" class="border">
        </div>
        <div class="flex gap-1">
            <label>harga: </label>
            <input type="number" name="harga" value="<?= $harga ?>" class="border">
        </div>
        <div class="flex gap-1">
            <label>stok: </label>
            <input type="number" name="stok" value="<?= $stok ?>" class="border">
        </div>

        <div class="flex justify-end gap-1">
            <a href="?page=menu" class="border">Batal</a>
            <button type="submit" name="simpan" class="bg-green-500">Simpan</button>
        </div>
    </form>
</div>