<?php
$db = new Database();
$tanggal = '2025-09-14'; // Ganti dengan tanggal yang diinginkan
$omsetHarian = $db->select("select sum(total) as omset from transaksi where date(created_at)=?",'s',[$tanggal])[0]['omset'];
$transaksiHarian = $db->select("select count(*) as transaksi from transaksi where date(created_at)=?",'s',[$tanggal])[0]['transaksi'];

$pesananHarian = $db->select("select sum(p.jumlah) as pesanan from pesanan p join transaksi t on t.id=p.id_transaksi where date(t.created_at)=?",'s',[$tanggal])[0]['pesanan'];

$makananTerlaris = $db->select("select mn.nama_menu, sum(p.jumlah) as terjual from pesanan p join menu mn on mn.id=p.id_menu join transaksi t on p.id_transaksi=t.id where date(t.created_at)=? and mn.kategori='makanan' group by mn.id order by terjual desc limit 1",'s',[$tanggal])[0]['nama_menu'];

$minumanTerlaris = $db->select("select mn.nama_menu, sum(p.jumlah) as terjual from pesanan p join menu mn on mn.id=p.id_menu join transaksi t on p.id_transaksi=t.id where date(t.created_at)=? and mn.kategori='minuman' group by mn.id order by terjual desc limit 1",'s',[$tanggal])[0]['nama_menu'];
?>

<div class="flex my-10 mx-12">
    <!-- wrapper card -->
    <div class="flex justify-between gap-5 w-full">
        <!-- card omset -->
        <div class="flex flex-col gap-1 border px-2 py-5 rounded-2xl">
            <h1 class="text-xl">Total Omset Hari ini:</h1>
            <h3 class="text-3xl font-bold"><?= ($omsetHarian) ? formatRupiah($omsetHarian) : formatRupiah(0) ?></h3>
        </div>
        <!-- card transaksi   -->
        <div class="flex flex-col gap-1 border px-2 py-5 rounded-2xl">
            <h1 class="text-xl">Total Transaksi Hari ini:</h1>
            <h3 class="text-3xl font-bold"><?= isset($transaksiHarian) ? $transaksiHarian : 0 ?></h3>
        </div>

        <!-- card pesanan -->
        <div class="flex flex-col gap-1 border px-2 py-5 rounded-2xl">
            <h1 class="text-xl">Total Pesanan Hari ini:</h1>
            <h3 class="text-3xl font-bold"><?= isset($pesananHarian) ? $pesananHarian : 0 ?></h3>
        </div>
        <!-- card makanan terlaris -->
        <div class="flex flex-col gap-1 border px-2 py-5 rounded-2xl">
            <h1 class="text-xl">Makanan Terlaris Hari ini:</h1>
            <h3 class="text-3xl font-bold"><?= isset($makananTerlaris) ? $makananTerlaris : 0 ?></h3>
        </div>
        <!-- card minuman terlaris -->
        <div class="flex flex-col gap-1 border px-2 py-5 rounded-2xl">
            <h1 class="text-xl">Minuman Terlaris Hari ini:</h1>
            <h3 class="text-3xl font-bold"><?= isset($minumanTerlaris) ? $minumanTerlaris : 0 ?></h3>
        </div>
    </div>
</div>