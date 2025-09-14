<?php
if(isset($_POST['tambah'])){
    $nomor_meja = $_POST['nomor_meja'];
    $status = $_POST["status"] ?? 'kosong';
    $stmt = $koneksi->prepare("insert into meja (nomor_meja,status) values (?,?)");
    $stmt->bind_param("is", $nomor_meja, $status);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php?page=meja');
    exit();
}

?>

<form method="post" class="flex ml-6 flex-col border w-1/6 p-2 gap-1">
    <label>Nomor Meja:</label>
    <input type="number" name="nomor_meja" placeholder="masukkan Nomor Meja" class="border">
    <label>Status:</label>
    <select name="status" class="border">
        <option value="kosong">Kosong</option>
        <option value="terisi">Terisi</option>
        <option value="reserved">Reserved</option>
    </select>
    <div class="flex justify-end gap-1">
        <a href="?page=meja" class="bg-red-500">batal</a>
        <button type="submit" name="tambah" class="bg-green-500">Tambah</button>
    </div>
</form>