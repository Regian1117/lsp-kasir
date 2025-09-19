<?php
$id_transaksi = $_POST['id_transaksi'];

function getPesanan($koneksi, $id)
{
    $stmt = $koneksi->prepare("select p.*, mn.*, t.total from pesanan p join menu mn on p.id_menu=mn.id join transaksi t on t.id=p.id_transaksi where t.id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result();
}

// Inisialisasi keranjang
include 'admin/pesanan/ClassCart.php';
$cart = new Cart($koneksi);

if ($id_transaksi) {
    $cart->initDB($id_transaksi);
}

if (isset($_POST['tambah'])) {
    $id_menu = (int) $_POST['id_menu'];
    $cart->tambah($id_menu);
}

if (isset($_POST['kurang'])) {
    $id_menu = (int) $_POST['id_menu'];
    $cart->kurang($id_menu);
}

if (isset($_POST['hapus'])) {
    $id_menu = (int) $_POST['id_menu'];
    $cart->hapus($id_menu);
}

if (isset($_POST['keranjang'])) {
    $id_menu = (int) $_POST['id_menu'];
    $jumlah = (int) $_POST['jumlah'];
    $cart->tambahKeranjang($id_menu, $jumlah, $koneksi);
}

$items = $cart->getItems();

// Fungsi ambil menu
function getMenu($kategori)
{
    global $koneksi;
    $stmt = $koneksi->prepare("SELECT * FROM menu WHERE kategori=?");
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    return $stmt->get_result();
}
$makanan = getMenu('makanan');
$minuman = getMenu('minuman');
?>

<!-- pesanan dan menu -->
<div class="flex">

    <!-- pesanan -->
    <div class="flex flex-col gap-4 w-2/5 border">
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <!-- menu container -->
            <div class="flex justify-between">
                <!-- nama & foto -->
                <div class="flex gap-2">
                    <img src="<?= $item['foto'] ?>" class="w-28 h-20 object-cover">
                    <!-- nama menu & harga -->
                    <div class="flex flex-col">
                        <h1 class="font-bold text-xl"><?= $item['nama_menu'] ?></h1>
                        <h3 class="text-lg">Rp <?= number_format($item['harga'], 0, ',', '.') ?></h3>
                    </div>
                </div>
                <!-- jumlah pesanan -->
                <div class="flex border rounded-full h-auto my-auto items-center font-bold">
                    <!-- kurang -->
                    <form method="post">
                        <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                        <input type="hidden" name="id_menu" value="<?= $item['id_menu'] ?>">
                        <?php if ($item['jumlah'] == 1): ?>
                            <button type="submit" name="hapus" class="p-3 rounded-full"><i class="fa-solid fa-trash text-xs"></i></button>
                        <?php else: ?>
                            <button type="submit" name="kurang" class="p-3 rounded-full"><i class="fa-solid fa-minus text-xs"></i></button>
                        <?php endif; ?>
                    </form>
                    <!-- input -->
                    <input type="number" value="<?= $item['jumlah'] ?>" class="jumlah-input w-5 min-w-0 text-center focus:outline-none" readonly>
                    <!-- tambah -->
                    <form method="post">
                        <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                        <input type="hidden" name="id_menu" value="<?= $item['id_menu'] ?>">
                        <button type="submit" name="tambah" class="p-3 rounded-full"><i class="fa-solid fa-plus text-xs"></i></button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- menu -->
    <div class="flex w-3/5 border">

        <div class="flex">
            <div class="w-2/3 mt-5 mx-5">
                <!-- Makanan -->
                <h1 class="text-3xl my-5">Makanan</h1>
                <div class="wrapper flex gap-4">
                    <?php while ($row = $makanan->fetch_assoc()): ?>
                        <!-- card -->
                        <div class="w-60 bg-white rounded-lg shadow-md flex flex-col gap-2 p-2">
                            <img src="<?= $row['foto'] ?>" alt="foto makanan" class="w-full h-40 object-cover">
                            <div class="">
                                <h3 class="text-xl"><?= $row['nama_menu'] ?></h3>
                                <h2 class="text-lg"><?= number_format($row['harga'], 0, ',', '.') ?></h2>
                                <p class="text-xs">stok : <?= $row['stok'] ?></p>
                            </div>
                            <div class="flex gap-2">
                                <form method="post" class="inline w-full px-2 py-1 bg-red-400 rounded-lg text-white text-center">
                                    <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                                    <input type="hidden" name="id_menu" value="<?= $row['id'] ?>">
                                    <input type="number" name="jumlah" placeholder="masukkan jumlah" value=1>
                                    <button name="keranjang" class="bg-green-700 py-2 w-full">Tambah</button>
                                </form>

                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <!-- Minuman -->
                <h1 class="text-3xl my-5">Minuman</h1>
                <div class="wrapper flex gap-4">
                    <?php while ($row = $minuman->fetch_assoc()): ?>
                        <!-- card -->
                        <div class="w-60 bg-white rounded-lg shadow-md flex flex-col gap-2 p-2">
                            <img src="<?= $row['foto'] ?>" alt="foto minuman" class="w-full h-40 object-cover">
                            <div class="">
                                <h3 class="text-xl"><?= $row['nama_menu'] ?></h3>
                                <h2 class="text-lg"><?= number_format($row['harga'], 0, ',', '.') ?></h2>
                                <p class="text-xs">stok : <?= $row['stok'] ?></p>
                            </div>
                            <div class="flex gap-2">
                                <form method="post" class="inline w-full px-2 py-1 bg-red-400 rounded-lg text-white text-center">
                                    <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                                    <input type="hidden" name="id_menu" value="<?= $row['id'] ?>">
                                    <input type="number" name="jumlah" placeholder="masukkan jumlah" value=1>
                                    <button name="keranjang">Tambah</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>
    </div>