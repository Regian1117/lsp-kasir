<?php
$id_transaksi = $_POST['id_transaksi'];

function getPesanan($koneksi, $id)
{
    $stmt = $koneksi->prepare("select p.*, mn.*, t.total from pesanan p join menu mn on p.id_menu=mn.id join transaksi t on t.id=p.id_transaksi where t.id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result();
}

$result = getPesanan($koneksi, $id_transaksi);
?>


<!-- pesanan -->
<div class="flex flex-col gap-4 w-2/5 border">
    <?php while ($row = $result->fetch_assoc()): ?>
        <!-- menu container -->
        <div class="flex justify-between">
            <!-- nama & foto -->
            <div class="flex gap-2">
                <img src="<?= $row['foto'] ?>" class="w-30 h-20 object-cover">
                <!-- nama menu & harga -->
                <div class="flex flex-col">
                    <h1 class="font-bold text-xl"><?= $row['nama_menu'] ?></h1>
                    <h3 class="text-lg">Rp <?= number_format($row['harga'], 0, ',', '.') ?></h3>
                </div>
            </div>
            <!-- jumlah pesanan -->
            <div class="flex border rounded-full h-auto my-auto items-center font-bold">
                <!-- kurang -->
                <button class="p-3 rounded-full" onclick="editJumlah(this,'kurang')"><i class="fa-solid fa-minus text-xs"></i></button>
                <!-- input -->
                <input type="number" id="jumlah" value="<?= $row['jumlah'] ?>" class="jumlah-input w-5 min-w-0 text-center focus:outline-none">
                <!-- tambah -->
                <button class="p-3 rounded-full" onclick="editJumlah(this,'tambah')"><i class="fa-solid fa-plus text-xs"></i></button>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<script>
    function editJumlah(button, mode){
        const container = button.closest('div');
        const input= container.querySelector('.jumlah-input')
        let val = Number(input.value) || 0;
        input.value = (mode==="tambah") ? val + 1 : Math.max(1, val -1);
    }
</script>