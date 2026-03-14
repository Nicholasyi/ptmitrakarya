<?php
include('koneksi.php');
function formatrupiah($angka)
{
    $hasilrupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasilrupiah;
}
$ambil = $koneksi->query("SELECT*FROM penjualan where notajual='$_GET[id]' group by notajual");
$pecah = $ambil->fetch_assoc();
?>
<html>

<head>
    <title>Nota</title>
    <style>
        /* @page {
            size: 88mm 170mm;
            margin: 3mm;
        } */
        @page {
            margin: 3mm;
        }
    </style>
    <style>
        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }
    </style>
</head>

<body style='font-family:tahoma; font-size:8pt;'>
    <center>
        <table style='width:230px; font-size:16pt; font-family:calibri; border-collapse: collapse;' border='0'>

            <tr>
                <td colspan="2">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-size:11pt">Nota</span>
                </td>
                <td>
                    <span style="font-size:11pt"> : <?= $pecah['kodenota'] ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-size:11pt">Tanggal</span>
                </td>
                <td>
                    <span style="font-size:11pt"> : <?= date("d-m-Y", strtotime($pecah['tanggalpenjualan'])) ?></span>
                </td>
            </tr>
        </table>

        <br>
        <table cellspacing='0' cellpadding='0' style='width:230px; font-size:11pt; font-family:calibri; border-collapse: collapse;' border='0'>
            <?php
            $total = 0;
            $ambildaftarbarangdetail = $koneksi->query("SELECT*FROM penjualan where notajual = '$pecah[notajual]'");
            while ($daftarbarangdetail = $ambildaftarbarangdetail->fetch_assoc()) {
                $total += $daftarbarangdetail['total'];
            ?>
                <tr>
                    <td><?php echo substr($daftarbarangdetail['namabarang'], 0, 50); ?></td>
                </tr>
                <tr>
                    <td style=' vertical-align:top; text-align:left;'><?php echo formatrupiah($daftarbarangdetail['harga']) ?> x <?php echo $daftarbarangdetail['jumlah']; ?></td>
                    <td style='vertical-align:top; text-align:right;'><?php echo formatrupiah($daftarbarangdetail['total']) ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        ---------------------------------------------------
                    </td>
                </tr>
            <?php } ?>
        </table>
        <br>
        <table style='width:230px; font-size:11pt; font-family:calibri;  border-collapse: collapse;' border='0'>
            <tr>
                <td width="100px">
                    <div style='text-align:left'>Total</div>
                </td>
                <td>: <?php echo formatrupiah($total) ?></td>
            </tr>
            <tr>
                <td width="100px">
                    <div style='text-align:left'>Uang Pembeli</div>
                </td>
                <td>: <?php echo formatrupiah($pecah['uangpembeli']) ?></td>
            </tr>
            <tr>
                <td width="100px">
                    <div style='text-align:left'>Kembalian </div>
                </td>
                <td>: <?php echo formatrupiah($pecah['kembalian']) ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    --------------------------------------------------
                </td>
            </tr>
        </table>
        <table style='width:230px; font-size:11pt;' cellspacing='2'>
            <tr>
                <td align='center'>
                    TERIMA KASIH ATAS PEMBELIAN ANDA</br>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
<script>
    window.print();
</script>