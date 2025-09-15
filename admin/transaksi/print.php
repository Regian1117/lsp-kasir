<?php
$id = $_POST['id'];

$stmt = $koneksi->prepare(
    'select
        mn.nama_menu,
        p.jumlah,
        p.harga,
        p.subtotal,
        t.kode_transaksi,
        u.nama as kasir,
        t.id_meja,
        t.total,
        t.created_at,
        t.nama_pelanggan
        from transaksi t
        join pesanan p on t.id = p.id_transaksi
        join users u on t.id_user=u.id
        join menu mn on p.id_menu=mn.id
        where t.id=?'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$total = 0;
?>
<style>
    /* Styling khusus struk */
    .struk {
        padding: 10px;
        font-family: monospace;
        font-size: 10pt;
    }

    .line {
        border-top: 1px dashed #000;
        margin: 5px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 2px 0;
    }

    /* Saat print: sembunyikan elemen dengan class no-print */
    @media print {
        .no-print {
            display: none !important;
        }

        @page {
            size: 58mm auto;
            margin: 2mm;
        }
    }
</style>

<body onload="window.print()">

    <div class="text-center flex flex-col text-2xl font-bold">
        <h1>Kasir Geming</h1>
        <h3>Jl. Pisangan</h3>
        -----------------------------
    </div>

    <table>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $total = $row['total']; ?>
            <tr>
                <td><?= $row['nama_menu'] ?></td>
                <td align="right"><?= $row['jumlah'] ?> x <?= $row['harga'] ?></td>
                <td align="right"><?= $row['subtotal'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="line"></div>
    <table>
        <tr>
            <td><strong>Total</strong></td>
            <td align="right"><strong><?= $total ?></strong></td>
        </tr>
    </table>

    <div class="center">
        Terima Kasih 🙏<br />
        ~~~~~~~~~~~~~~~
    </div>

</body>

</html>