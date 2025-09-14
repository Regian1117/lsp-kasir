<?php
// Tambah ke keranjang
if (isset($_POST['keranjang'])) {
    $id_menu = (int) $_POST['id'];
    $jumlah  = (int) $_POST['jumlah'];

    if ($jumlah > 0) {
        // Ambil data menu dari DB
        $stmt = $koneksi->prepare("SELECT * FROM menu WHERE id = ?");
        $stmt->bind_param("i", $id_menu);
        $stmt->execute();
        $menu = $stmt->get_result()->fetch_assoc();

        if ($menu) {
            // Cek apakah item sudah ada di keranjang
            $found = false;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as &$c) {
                    if ($c['id_menu'] == $id_menu) {
                        $c['jumlah'] += $jumlah;
                        $c['subtotal'] = $c['harga'] * $c['jumlah'];
                        $found = true;
                        break;
                    }
                }
            }

            // Kalau belum ada, tambah baru
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id_menu'   => $menu['id'],
                    'nama_menu' => $menu['nama_menu'],
                    'harga'     => $menu['harga'],
                    'jumlah'    => $jumlah,
                    'subtotal'  => $menu['harga'] * $jumlah
                ];
            }
        }
    }

    header("Location: index.php?page=pesanan");
    exit();
}

// Kurangi item
if (isset($_POST['kurangi'])) {
    $id_menu = (int) $_POST['id_menu'];
    foreach ($_SESSION['cart'] as $key => &$c) {
        if ($c['id_menu'] == $id_menu) {
            $c['jumlah']--;
            if ($c['jumlah'] <= 0) {
                unset($_SESSION['cart'][$key]); // hapus kalau jumlah 0
            } else {
                $c['subtotal'] = $c['harga'] * $c['jumlah'];
            }
            break;
        }
    }
    header("Location: index.php?page=pesanan");
    exit();
}

// Hapus item langsung
if (isset($_POST['hapus'])) {
    $id_menu = (int) $_POST['id_menu'];
    foreach ($_SESSION['cart'] as $key => $c) {
        if ($c['id_menu'] == $id_menu) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    header("Location: index.php?page=pesanan");
    exit();
}

// Tambah jumlah langsung dari keranjang
if (isset($_POST['tambah'])) {
    $id_menu = (int) $_POST['id_menu'];
    foreach ($_SESSION['cart'] as &$c) {
        if ($c['id_menu'] == $id_menu) {
            $c['jumlah']++;
            $c['subtotal'] = $c['harga'] * $c['jumlah'];
            break;
        }
    }
    header("Location: index.php?page=pesanan");
    exit();
}

if (isset($_POST['checkout'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $stmt = $koneksi->prepare("insert into pesanan (id_transaksi,id_menu,jumlah,harga,subtotal) values (?,?,?,?,?)");
    foreach ($_SESSION['cart'] as $c) {
        $stmt->bind_param('siiii', $id_transaksi, $c["id_menu"], $c['jumlah'], $c['harga'], $c['subtotal']);
        $stmt->execute();
    }
    $stmt->close();
    unset($_SESSION['cart']);

    // update total table transaksi

    // ambil total harga dari db
    $stmt = $koneksi->prepare('select sum(subtotal) total from pesanan where id_transaksi = ?');
    $stmt->bind_param('i', $id_transaksi);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total = $row['total'] ?? 0;

    //update transaksi
    $stmt = $koneksi->prepare('update transaksi set total=? where id =? ');
    $stmt->bind_param('ii', $total, $id_transaksi);
    $stmt->execute();
    $stmt->close();
}


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
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="number" name="jumlah" placeholder="masukkan jumlah" value=1>
                            <button name="keranjang">Tambah</button>
                        </form>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>
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
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="number" name="jumlah" placeholder="masukkan jumlah" value=1>
                            <button name="keranjang">Tambah</button>
                        </form>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- keranjang -->
    <div class="w-1/3 bg-gray-100 p-4 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Keranjang</h2>
        <?php if (!empty($_SESSION['cart'])): ?>
            <ul class="space-y-2">
                <?php $total = 0;
                foreach ($_SESSION['cart'] as $c): ?>
                    <li class="flex justify-between items-center bg-white p-2 rounded shadow">
                        <div>
                            <span class="font-semibold"><?= $c['nama_menu'] ?></span>
                            <span class="text-sm text-gray-600">x <?= $c['jumlah'] ?></span>
                            <p class="text-xs text-gray-500">Rp <?= number_format($c['harga'], 0, ',', '.') ?> / pcs</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <!-- Kurangi -->
                            <form method="post">
                                <input type="hidden" name="id_menu" value="<?= $c['id_menu'] ?>">
                                <button type="submit" name="kurangi" class="px-2 bg-yellow-400 text-white rounded">-</button>
                            </form>
                            <!-- Tambah -->
                            <form method="post">
                                <input type="hidden" name="id_menu" value="<?= $c['id_menu'] ?>">
                                <button type="submit" name="tambah" class="px-2 bg-green-500 text-white rounded">+</button>
                            </form>
                            <!-- Hapus -->
                            <form method="post">
                                <input type="hidden" name="id_menu" value="<?= $c['id_menu'] ?>">
                                <button type="submit" name="hapus" class="px-2 bg-red-500 text-white rounded">x</button>
                            </form>
                            <!-- Subtotal -->
                            <span class="ml-2 font-semibold">Rp <?= number_format($c['subtotal'], 0, ',', '.') ?></span>
                        </div>
                    </li>
                    <?php $total += $c['subtotal']; ?>
                <?php endforeach; ?>
            </ul>
            <form method="post" class="flex flex-col mt-2">
                <select name="id_transaksi" id="idtr">
                    <?php
                    $stmt = $koneksi->prepare("select id, kode_transaksi, id_meja from transaksi");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <option value="<?= $row['id'] ?>">kode : <?= $row['kode_transaksi'] ?> meja : <?= $row['id_meja'] ?></option>
                    <?php } ?>
                </select>
                <div class="mt-1 font-bold">Total: Rp <?= number_format($total, 0, ',', '.') ?></div>
                <button type="submit" name="checkout" class="w-1/3 mt-2 px-4 py-2 bg-blue-500 text-white rounded">Checkout</button>
            </form>
        <?php else: ?>
            <p>Keranjang kosong</p>
        <?php endif; ?>
    </div>
</div>