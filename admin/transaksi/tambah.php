<?php
if (isset($_POST['tambah'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'] ?? '';
    $id_meja        = $_POST['id_meja'];
    $total          = $_POST['total'] ?? 0;
    $status         = $_POST['status'];

    // kode transaksi bisa auto-generate
    $kode_transaksi = generateKodeTransaksi($koneksi);
    $id_user        = $_SESSION['user_id']; // waiter/kasir yang login

    $stmt = $koneksi->prepare("INSERT INTO transaksi 
        (kode_transaksi, nama_pelanggan, id_meja, id_user, total, status) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiis", $kode_transaksi, $nama_pelanggan, $id_meja, $id_user, $total, $status);

    if ($stmt->execute()) {
        // Update meja jadi terisi
        $update = $koneksi->prepare("UPDATE meja SET status = 'terisi' WHERE id = ?");
        $update->bind_param("i", $id_meja);
        $update->execute();
    }
    header('Location: index.php?page=transaksi');
    exit();
}
?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Transaksi</h1>

    <form method="post" class="space-y-4 bg-white p-6 rounded-lg shadow-md max-w-lg">

        <!-- Nama Pelanggan -->
        <div>
            <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                required>
        </div>

        <!-- Pilih Meja -->
        <div>
            <label for="id_meja" class="block text-sm font-medium text-gray-700">Nomor Meja</label>
            <select name="id_meja" id="id_meja"
                class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                required>
                <option value="">-- Pilih Meja --</option>
                <?php
                $meja = $koneksi->query("SELECT id, nomor_meja FROM meja where status='kosong' ORDER BY nomor_meja ASC");
                while ($m = $meja->fetch_assoc()):
                ?>
                    <option value="<?= $m['id'] ?>"><?= $m['nomor_meja'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Total Harga -->
        <div>
            <label for="total" class="block text-sm font-medium text-gray-700">Total Harga</label>
            <input type="number" name="total" id="total"
                class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                required>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <div class="flex space-x-2">
            <a href="?page=transaksi"
                class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" name="tambah"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Tambah
            </button>
        </div>
    </form>
</div>