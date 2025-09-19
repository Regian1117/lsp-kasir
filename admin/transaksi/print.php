<?php
$id = $_POST['id'];

$stmt = $koneksi->prepare("select t.*, u.nama from transaksi t join users u on t.id_user=u.id where t.id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$resultTransaksi = $stmt->get_result()->fetch_assoc();


$stmt = $koneksi->prepare(
    'select
        mn.nama_menu,
        p.jumlah,
        p.harga,
        p.subtotal,
        t.total
        from transaksi t
        join pesanan p on t.id = p.id_transaksi
        join menu mn on p.id_menu=mn.id
        where t.id=?'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultPesanan = $stmt->get_result();
$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Print specific styling */
        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                size: 58mm auto;
                margin: 2mm;
            }
            
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        /* Custom font for receipt look */
        .receipt-font {
            font-family: 'Courier New', Courier, monospace;
        }
        
        /* Dotted line styling */
        .dotted-line {
            border-top: 1px dashed #000;
            width: 100%;
        }
    </style>
</head>

<body onload="window.print()" class="bg-white">

    <!-- Receipt Container -->
    <div class="max-w-xs mx-auto bg-white receipt-font text-xs leading-tight">
        
        <!-- Header -->
        <div class="text-center py-2 border-b border-dashed border-gray-600">
            <h1 class="text-lg font-bold uppercase tracking-wide">KASIR GEMING</h1>
            <p class="text-xs mt-1">Jl. Pisangan</p>
            <p class="text-xs">Jakarta, Indonesia</p>
            <div class="dotted-line mt-2"></div>
        </div>

        <!-- Transaction Info -->
        <div class="py-3 space-y-1 text-xs">
            <div class="flex justify-between">
                <span class="font-medium">No. Transaksi:</span>
                <span><?= $resultTransaksi['kode_transaksi'] ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Pelanggan:</span>
                <span class="uppercase"><?= $resultTransaksi['nama_pelanggan'] ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Kasir:</span>
                <span class="capitalize"><?= $resultTransaksi['nama'] ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Waktu:</span>
                <span><?= $resultTransaksi['created_at'] ?></span>
            </div>
        </div>

        <div class="dotted-line"></div>

        <!-- Items Header -->
        <div class="py-2">
            <div class="flex justify-between text-xs font-semibold">
                <span>Item</span>
                <span>Subtotal Item</span>
            </div>
        </div>

        <!-- Order Items -->
        <div class="space-y-1">
            <?php while ($row = $resultPesanan->fetch_assoc()): ?>
                <?php $total = $row['total']; ?>
                <div class="py-1">
                    <!-- Item name -->
                    <div class="flex justify-between">
                        <span class="font-medium uppercase text-xs"><?= $row['nama_menu'] ?></span>
                        <span class="text-xs font-bold"><?= formatRupiah($row['subtotal']) ?></span>
                    </div>
                    <!-- Quantity and price -->
                    <div class="text-xs text-gray-600 ml-2">
                        <?= $row['jumlah'] ?> x <?= formatRupiah($row['harga']) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="dotted-line my-2"></div>

        <!-- Total Section -->
        <div class="py-2">
            <div class="flex justify-between text-sm font-medium">
                <span>Subtotal : </span>
                <span class="text-sm"><?= formatRupiah($total) ?></span>
            </div>
            <div class="flex justify-between text-sm font-medium">
                <span>PPN 12% : </span>
                <span class="text-sm"><?= formatRupiah($total*.12) ?></span>
            </div>
            <div class="flex justify-between text-sm font-semibold">
                <span class="text-lg">Total : </span>
                <span class="text-lg"><?= formatRupiah($total+$total*.12) ?></span>
            </div>
        </div>

        <div class="dotted-line my-2"></div>

        <!-- Footer -->
        <div class="text-center py-3 text-xs">
            <p class="font-medium">TERIMA KASIH</p>
            <p class="mt-1">Atas kunjungan Anda</p>
            <p class="mt-2">🙏</p>
            <div class="dotted-line mt-2"></div>
            <p class="mt-2 text-xs opacity-75">~ Semoga Hari Anda Menyenangkan ~</p>
        </div>

    </div>

</body>
</html>