<?php
$stmt = $koneksi->prepare("
    SELECT t.*, u.nama AS waiter, mj.nomor_meja 
    FROM transaksi t 
    JOIN users u ON t.id_user = u.id 
    JOIN meja mj ON t.id_meja = mj.id 
    ORDER BY t.kode_transaksi asc
");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="p-6">
    <a href="?page=transaksi&action=tambah" class="bg-blue-500 rounded-lg text-white p-3">Tambah Transaksi</a>
    <h1 class="text-2xl font-bold mb-4 mt-4">Daftar Transaksi</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Kode Transaksi</th>
                    <th class="px-4 py-2 border">Nama Pelanggan</th>
                    <th class="px-4 py-2 border">Meja</th>
                    <th class="px-4 py-2 border">Waiter</th>
                    <th class="px-4 py-2 border">Total Harga</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Waktu</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['kode_transaksi']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['nomor_meja']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['waiter']) ?></td>
                        <td class="px-4 py-2 border">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td class="px-4 py-2 border">
                            <span class="px-2 py-1 rounded text-white 
                                <?= $row['status'] === 'paid' ? 'bg-green-500' : 'bg-yellow-500' ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['created_at']) ?></td>
                        <td class="px-4 py-2 border space-x-2 flex justify-center">
                            <!-- Form Edit -->
                            <form method="post" action="?page=transaksi&action=update" class="inline">
                                <input type="hidden" name="id_transaksi" value="<?= $row['id'] ?>">
                                <input type="hidden" name="nama_pelanggan" value="<?= $row['nama_pelanggan'] ?>">
                                <input type="hidden" name="id_meja" value="<?= $row['id_meja'] ?>">
                                <input type="hidden" name="status" value="<?= $row['status'] ?>">
                                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</button>
                            </form>

                            <!-- Form Hapus -->
                            <form method="post" action="?page=transaksi&action=hapus">
                                <input type="hidden" name="id_transaksi" value="<?= $row['id'] ?>">
                                <input type="hidden" name="kode_transaksi" value="<?=$row['kode_transaksi']?>">
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
                            </form>

                            <!-- form print -->
                            <form action="?page=transaksi&action=print" method="post">
                                <input type="hidden" name="id" value="<?= $row['id']?>">
                                <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Print</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
