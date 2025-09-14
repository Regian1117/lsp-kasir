<?php

$id = $_POST['id'];
$nomor_meja = $_POST['nomor_meja'];
$status = $_POST['status'];

if (isset($_POST["update"])) {
    $id = $_POST['id'];
    $nomor_meja = $_POST['nomor_meja'];
    $status = $_POST['status'];

    $sql = "update meja set nomor_meja=?, status=? where id=?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('isi', $nomor_meja, $status, $id);
    $stmt->execute();
    header('Location: index.php?page=meja');
    exit();
}
?>

<form method="post" class="flex ml-6 flex-col border w-1/6 p-2 gap-1">
    <input type="hidden" name="id" value="<?= $id ?>" hidden>
    <label>Nomor Meja:</label>
    <input type="number" name="nomor_meja" value="<?= $nomor_meja ?>" class="border">
    <label>Status:</label>
    <select name="status" class="border">
        <option value="kosong" <?= ($status == 'kosong') ? 'selected' : '' ?>>Kosong</option>
        <option value="terisi" <?= ($status == 'terisi') ? 'selected' : '' ?>>Terisi</option>
        <option value="reserved" <?= ($status == 'reserved') ? 'selected' : '' ?>>Reserved</option>
    </select>
    <div class="flex justify-end gap-1">
        <a href="?page=meja" class="bg-red-500">batal</a>
        <button type="submit" name="update" class="bg-green-500">Simpan</button>
    </div>
</form>