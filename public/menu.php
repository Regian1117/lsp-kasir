<?php
include '../includes/header.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
}

if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'waiter') {
    header("Location: dashboard.php");
    exit();
}

include '../includes/koneksi.php';

function getMenu($kategori){
    global $koneksi;
    $stmt = $koneksi->prepare("select * from menu where kategori=?");
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    return $stmt->get_result();
}
$makanan = getMenu('makanan');
$minuman = getMenu('minuman');
?>

<div class="mt-5 mx-5">
    <div class=""><button class="px-4 py-2 bg-blue-400 rounded-lg text-white">Tambah Menu</button></div>
    <h1 class="text-3xl my-5">Makanan</h1>
    <div class="wrapper flex gap-4">
        <?php while($row = $makanan->fetch_assoc()):?>
            <!-- card -->
            <div class="w-60 bg-white rounded-lg shadow-md flex flex-col gap-2 p-2">
                <img src="<?= $row['foto']?>" alt="foto makanan" class="w-full h-40 object-cover">
                <div class="">
                    <h3 class="text-xl"><?=$row['nama_menu']?></h3>
                    <h2 class="text-lg"><?= number_format($row['harga'],0,',','.') ?></h2>
                    <p class="text-xs">stok : <?=$row['stok']?></p>
                </div>
                <div class="flex gap-2">
                    <button onclick="openEdit(<?=$row['id']?>,'<?=$row['nama_menu']?>',<?=$row['harga']?>,<?=$row['stok']?>)" class="w-full px-2 py-1 bg-green-400 rounded-lg text-white">Edit</button>
                    <button class="w-full px-2 py-1 bg-red-400 rounded-lg text-white">Hapus</button>
                </div>
            </div>
        <?php endwhile;?>
    </div>
</div>

<!-- modalEdit -->
 <!-- overlay -->
<div id="editOverlay" class="inset-0">
    <!-- modalCard -->
     <div class="">

     </div>
</div>

<script>
    function openEdit(id,nama_menu,harga,stok){
    }
</script>
