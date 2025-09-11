<?php
include '../includes/header.php';
include '../includes/koneksi.php';

// Tambah meja
if (isset($_POST['tambah'])) {
    $nomor = $_POST['nomor_meja'];
    $status = $_POST['status'] ?? 'kosong';

    $stmt = $conn->prepare("INSERT INTO meja (nomor_meja, status) VALUES (?, ?)");
    $stmt->bind_param("ss", $nomor, $status);
    $stmt->execute();
    $stmt->close();

    header("Location: meja.php");
    exit();
}

// Hapus meja
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM meja WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: meja.php");
    exit();
}

// Edit meja
$editMeja = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM meja WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editMeja = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Update meja
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nomor = $_POST['nomor_meja'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE meja SET nomor_meja=?, status=? WHERE id=?");
    $stmt->bind_param("ssi", $nomor, $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: meja.php");
    exit();
}

// Filter
$filterStatus = $_GET['filter_status'] ?? '';
$filterNomor = $_GET['filter_nomor'] ?? '';

$where = [];
$params = [];
$types = '';

if ($filterStatus) {
    $where[] = "status = ?";
    $params[] = $filterStatus;
    $types .= 's';
}
if ($filterNomor) {
    $where[] = "nomor_meja LIKE ?";
    $params[] = "%$filterNomor%";
    $types .= 's';
}

$sql = "SELECT * FROM meja";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY nomor_meja ASC";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="flex flex-col">
    <h2 class="text-2xl font-bold mb-6">Entri Meja</h2>

    <!-- Form Tambah / Edit Meja -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <input type="hidden" name="id" value="<?= $editMeja['id'] ?? '' ?>">

            <div>
                <label class="block font-semibold mb-1">Nomor Meja</label>
                <input type="text" name="nomor_meja" required class="w-full border border-gray-300 rounded px-2 py-1" value="<?= $editMeja['nomor_meja'] ?? '' ?>">
            </div>

            <div>
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded px-2 py-1">
                    <?php
                    $statusOptions = ['kosong', 'terisi', 'reserved'];
                    foreach ($statusOptions as $statusOpt) {
                        $selected = ($editMeja['status'] ?? 'kosong') === $statusOpt ? 'selected' : '';
                        echo "<option value='$statusOpt' $selected>".ucfirst($statusOpt)."</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <?php if ($editMeja): ?>
                    <button type="submit" name="update" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full">Update Meja</button>
                <?php else: ?>
                    <button type="submit" name="tambah" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-full">Tambah Meja</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div>
                <label class="block font-semibold mb-1">Filter Status</label>
                <select name="filter_status" class="border border-gray-300 rounded px-2 py-1">
                    <option value="">Semua</option>
                    <?php foreach ($statusOptions as $statusOpt): ?>
                        <option value="<?= $statusOpt ?>" <?= ($filterStatus === $statusOpt ? 'selected' : '') ?>><?= ucfirst($statusOpt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Filter Nomor Meja</label>
                <input type="text" name="filter_nomor" placeholder="Cari nomor..." class="border border-gray-300 rounded px-2 py-1" value="<?= htmlspecialchars($filterNomor) ?>">
            </div>

            <div>
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Filter</button>
            </div>
        </form>
    </div>

    <!-- Tabel Meja -->
    <div class="bg-white p-6 rounded shadow">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nomor Meja</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= $no++ ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['nomor_meja']) ?></td>
                    <td class="px-4 py-2 capitalize"><?= $row['status'] ?></td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="meja.php?edit=<?= $row['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Edit</a>
                        <a href="meja.php?hapus=<?= $row['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('Hapus meja ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
