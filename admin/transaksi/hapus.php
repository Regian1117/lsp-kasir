<?php

$id = $_POST['id_transaksi'];
$kode_transaksi = $_POST["kode_transaksi"];

if(isset($_POST['hapus'])){
    $id = $_POST['id'];
    $stmt = $koneksi->prepare("delete from pesanan where id_transaksi=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $stmt = $koneksi->prepare("delete from transaksi where id=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php?page=transaksi');
    exit();
}
?>

<form method="post">
    <h1>Apakah anda yakin ingin menghapu transaksi <?=$kode_transaksi?> beserta semua pesanannya?</h1>
    <input type="hidden" name="id" value="<?= $id?>">
    <a href="?page=transaksi">Batal</a>
    <button type="submit" name="hapus">Hapus</button>
</form>