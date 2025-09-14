<?php

$id = $_POST['id'];
$nomor_meja = $_POST['nomor_meja'];
if(isset($_POST["hapus"])){
    $id = $_POST['id'];
    $sql = "DELETE from meja where id=?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php?page=meja');
    exit();
}
?>

<div class="p-4 ml-4 border w-1/6">
    <h1>apakah anda yakin ingin menghapus meja nomer <?= $nomor_meja ?></h1>
    <div class="flex gap-1">
        <a href="?page=meja" class="border">Batal</a>
        <form method="post">
            <input type="hidden" value="<?= $id ?>" name="id" >
            <button type="submit" name="hapus" class="bg-red-500">hapus</button>
        </form>
    </div>
</div>