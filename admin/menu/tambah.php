<?php 
include "includes/functions.php";
if (isset($_POST['tambah'])) {
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $foto_lama = $_POST['foto_lama'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] != 4) { // error 4 = no file
        $foto_baru = upload_foto($_FILES['foto'], 'uploads/', $nama_menu); //func dari includes/function
        if ($foto_baru) {
            // hapus foto lama jika ada
            if (file_exists('uploads/' . $foto_lama)) {
                unlink('uploads/' . $foto_lama);
            }
            $foto = $foto_baru;
        } else {
            $foto = $foto_lama; // gagal upload, tetap pakai lama
        }
    } else {
        $foto = $foto_lama; // tidak ganti foto
    }

    $stmt = $koneksi->prepare("insert into menu (nama_menu, harga, stok, foto, kategori) values (?,?,?,?,?)");
    $stmt->bind_param('siiss', $nama_menu, $harga, $stok, $foto, $kategori);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php?page=menu');
    exit();
}
?>
<div class="w-fit">
    <form method="post" enctype="multipart/form-data" class="flex flex-col border gap-1">
        <div class="bg-[url(uploads/placeholder.png)] bg-cover bg-center h-64 w-full flex items-end">
            <p class="bg-blue-500 p-3 text-white">Ganti Foto</p>
            <input type="file" name="foto" accept="image/*">
            <input type="hidden" name="foto_lama" value="uploads/placeholder.png">
        </div>
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="flex gap-1">
            <label>Nama Menu: </label>
            <input type="text" name="nama_menu" placeholder="masukkan nama menu" class="border">
        </div>
        <div class="flex gap-1">
            <label>harga: </label>
            <input type="number" name="harga" placeholder="masukkan harga" class="border">
        </div>
        <div class="flex gap-1">
            <label>stok: </label>
            <input type="number" name="stok" placeholder="masukkan stok" class="border">
        </div>
        <div class="flex gap-1">
            <label>Kategori: </label>
            <select name="kategori" class="border">
                <option value="makanan">Makanan</option>
                <option value="minuman">Minuman</option>
            </select>
        </div>

        <div class="flex justify-end gap-1">
            <a href="?page=menu" class="border">Batal</a>
            <button type="submit" name="tambah" class="bg-green-500">Tambah</button>
        </div>
    </form>
</div>