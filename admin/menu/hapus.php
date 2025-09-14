<?php

$id = $_POST["id"];
$nama_menu = $_POST["nama_menu"];

if(isset($_POST['hapus'])){
    $id = $_POST["id"];
    $nama_menu = $_POST["nama_menu"];
    
    $stmt = $koneksi->prepare("delete from menu where id=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php?page=menu');
}
?>

<div class="p-4 ml-4 border w-1/6">
    <h1>apakah anda yakin ingin menghapus menu <?= $nama_menu ?>?</h1>
    <div class="flex gap-1">
        <a href="?page=menu" class="border">Batal</a>
        <form method="post">
            <input type="hidden" value="<?= $id ?>" name="id" >
            <button type="submit" name="hapus" class="bg-red-500">hapus</button>
        </form>
    </div>
</div>