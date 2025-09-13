<?php
include '../includes/header.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
}

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

// Update meja
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nomor = $_POST['nomer_meja'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE meja SET nomor_meja=?, status=? WHERE id=?");
    $stmt->bind_param("ssi", $nomor, $status, $id);
    $stmt->execute();
    $stmt->close();
}


$sql = "SELECT * FROM meja";
$sql .= " ORDER BY nomor_meja ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="flex flex-col">
    <h2 class="text-2xl font-bold mb-6">Entri Meja</h2>

    <!-- Tabel Meja -->
    <div class="bg-white p-6 rounded shadow">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nomor Meja</th>
                    <th class="px-4 py-2 flex items-center">Status<button><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 7H17M17 7H16M17 7V6M17 7V8M12.5 5H6C5.5286 5 5.29289 5 5.14645 5.14645C5 5.29289 5 5.5286 5 6V7.96482C5 8.2268 5 8.35779 5.05916 8.46834C5.11833 8.57888 5.22732 8.65154 5.4453 8.79687L8.4688 10.8125C9.34073 11.3938 9.7767 11.6845 10.0133 12.1267C10.25 12.5688 10.25 13.0928 10.25 14.1407V19L13.75 17.25V14.1407C13.75 13.0928 13.75 12.5688 13.9867 12.1267C14.1205 11.8765 14.3182 11.6748 14.6226 11.4415M20 7C20 8.65685 18.6569 10 17 10C15.3431 10 14 8.65685 14 7C14 5.34315 15.3431 4 17 4C18.6569 4 20 5.34315 20 7Z" stroke="#464455" stroke-linecap="round" stroke-linejoin="round"/></svg></button></th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?= $no++ ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['nomor_meja']) ?></td>
                        <td class="px-4 py-2 capitalize"><?= $row['status'] ?></td>
                        <td class="px-4 py-2 space-x-2">
                            <button onclick="openModal(<?= $row['id'] ?>,<?= $row['nomor_meja']?>,'<?=$row['status']?>')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
                            <button href="meja.php?hapus=<?= $row['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('Hapus meja ini?')">Hapus</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Overlay -->
<div
    id="modalOverlay"
    class="hidden fixed inset-0 bg-black/50 backdrop-blur-md flex items-center justify-center z-50">

    <!-- Modal Box -->
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 space-y-4 transform scale-95 opacity-0 transition-all duration-300"
        id="modalBox">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Edit Meja</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <!-- Form -->
        <form id="editForm" method="POST" class="space-y-4">
            <input type="number" name="id" id="id" hidden>
            <div>
                <label for="nomor_meja_modal" class="block text-sm font-medium">Nomor Meja:</label>
                <input
                    type="number"
                    name="nomer_meja"
                    id="nomer_meja_modal"
                    class="mt-1 block w-full border rounded-md"
                    required>
            </div>
            <div>
                <label for="status_modal" class="block text-sm font-medium">Status</label>

                <select name="status" id="status_modal" class="w-full border rounded-md">
                    <option value="kosong">Kosong</option>
                    <option value="terisi">Terisi</option>
                    <option value="reserved">reserved</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <button
                    type="button"
                    onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button
                    type="submit"
                    name="update"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    const modalOverlay = document.getElementById('modalOverlay');
    const modalBox = document.getElementById('modalBox');

    function openModal(id, nomer_meja, status) {
        document.getElementById('nomer_meja_modal').value = nomer_meja;
        document.getElementById('status_modal').value = status;
        document.getElementById('id').value = id;
        modalOverlay.classList.remove('hidden');
        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeModal() {
        modalBox.classList.remove('scale-100', 'opacity-100');
        modalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modalOverlay.classList.add('hidden');
        }, 300);
    }

    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
    });
</script>