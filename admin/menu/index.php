<?php
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
    <a href="?page=menu&action=tambah" class="px-4 py-3 bg-blue-400 rounded-lg text-white">+ Tambah Menu</a>
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
                    <form action="?page=menu&action=hapus" method="post" class="inline w-full px-2 py-1 bg-red-400 rounded-lg text-white text-center">
                        <input type="hidden" name="id" value="<?=$row['id']?>">
                        <button>Hapus</button>
                    </form>
                    <form action="?page=menu&action=update" method="post" class="inline w-full px-2 py-1 bg-green-400 rounded-lg text-white text-center">
                        <input type="hidden" name="id" value="<?=$row['id']?>">
                        <input type="hidden" name="nama_menu" value="<?=$row['nama_menu']?>">
                        <input type="hidden" name="harga" value="<?=$row['harga']?>">
                        <input type="hidden" name="stok" value="<?=$row['stok']?>">
                        <input type="hidden" name="foto" value="<?=$row['foto']?>">
                        <button type="submit">Edit</button>
                    </form>
                </div>
            </div>
        <?php endwhile;?>
    </div>
</div>