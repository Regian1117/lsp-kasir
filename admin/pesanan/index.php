<?php
$stmt = $koneksi->prepare("select * from transaksi");
$stmt->execute();
$result = $stmt->get_result();

function getPesanan($koneksi, $id)
{
    $stmt = $koneksi->prepare("select p.*, mn.*, t.total from pesanan p join menu mn on p.id_menu=mn.id join transaksi t on t.id=p.id_transaksi where t.id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<div class="flex flex-col gap-4">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="border p-4 flex flex-col gap-2">
            <h1><?=$row['kode_transaksi']?></h1>
            <h1>meja : <?= $row['id_meja'] ?> Nama : <?= $row['nama_pelanggan'] ?></h1>
            <?php $hasil = getPesanan($koneksi, $row['id']); ?>
            <div class="flex gap-3">
                <?php while ($row_pesanan = $hasil->fetch_assoc()): ?>
                    <div class="p-2 border">
                        <img src="<?=$row_pesanan['foto']?>" class="w-60 h-40 bg-fill">
                        <h1><?=$row_pesanan['nama_menu']?></h1>
                        <h3><?=$row_pesanan['harga']?> x <?=$row_pesanan['jumlah']?></h3>
                        <h3><?=$row_pesanan['subtotal']?></h3>
                    </div>
                <?php endwhile; ?>
            </div>
            <form action="?page=pesanan&action=input" method="post">
                <input type="hidden" name="id_transaksi" value="<?=$row['id']?>">
                <button class="bg-blue-500 p-2 text-white min-w-30 rounded-xl">Edit</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>