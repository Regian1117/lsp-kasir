<?php

$id = $_POST['id_transaksi'];
$nama_pelanggan = $_POST['nama_pelanggan'];
$status = $_POST['status'];


if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $status = $_POST['status'];

    $stmt = $koneksi->prepare("update transaksi set nama_pelanggan=?, status=? where id=?");
    $stmt->bind_param("ssi", $nama_pelanggan, $status, $id );
    $stmt->execute();
    $stmt->close();
    header('Location: index.php?page=transaksi');
}
?>


<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Transaksi</h1>

    <form method="post" class="space-y-4 bg-white p-6 rounded-lg shadow-md max-w-lg">

        <input type="hidden" name="id" value="<?= $id ?>">
        <!-- Nama Pelanggan -->
        <div>
            <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="<?= $nama_pelanggan ?>"
                class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                required>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="pending" <?= ($status == 'pending') ? "selected" : "" ?>>Pending</option>
                <option value="paid" <?= ($status == 'paid') ? "selected" : "" ?>>Paid</option>
                <option value="cancel" <?= ($status == 'cancel') ? "selected" : "" ?>>Cancel</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <div class="flex space-x-2">
            <a href="?page=transaksi"
                class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" name="simpan"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Simpan
            </button>
        </div>
    </form>
</div>