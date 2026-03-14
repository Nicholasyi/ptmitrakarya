<?php
function rupiah($angka)
{
    $hasilrupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasilrupiah;
}
include('config.php');
function formatrupiah($angka)
{
    $hasilrupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasilrupiah;
}
$ambil = $koneksi->query("SELECT*FROM penjualan_detail where orderDetailID='$_GET[id]' group by orderDetailID");
$pecah = $ambil->fetch_assoc();
?>
<html>

<head>
    <title>Nota</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }

        @page {
            /* size: 58mm 80mm ;   */
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

<body style='font-family:tahoma; font-size:8pt;padding-top:50px;'>
    <center>
        <!-- <img src="foto/koplogo.png" width="680px"> -->
        <div style="border: solid 1px;width:350px;padding:15px">
            <br>
            <br>
            <h2>Nota Penjualan <?= $pecah['orderID'] ?></h2>
            <br>
            <table style='width:350px; font-size:16pt; font-family:calibri; border-collapse: collapse;' border='0'>
                <tr>
                    <td width="100px">
                        <span style="font-size:11pt">No. Nota</span>
                    </td>
                    <td>
                        <span style="font-size:11pt"> : <?= $pecah['orderDetailID'] ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size:11pt">Tanggal</span>
                    </td>
                    <td>
                        <span style="font-size:11pt"> : <?= date("d-m-Y", strtotime($pecah['orderDate'])) ?></span>
                    </td>
                </tr>
            </table>
            <br><br>
            <table cellspacing='0' cellpadding='0' style='width:350px; font-size:12pt; font-family:calibri; border-collapse: collapse;' border='1'>
                <thead>
                    <tr>
                        <th style="padding:5px;margin:5px">No</th>
                        <th width="40%">Obat</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $nobelanja = 1;
                    $ambildaftarbarang = $koneksi->query("SELECT*FROM penjualan where notajual = '$pecah[notajual]'");
                    while ($daftarbarang = $ambildaftarbarang->fetch_assoc()) { ?>
                        <tr>
                            <td align="center" style="padding:5px;margin:5px"><?php echo $nobelanja; ?></td>
                            <td align="center"><?php echo $daftarbarang['namabarang'] ?></td>
                            <td style="padding:5px;margin:5px"><?php echo rupiah($daftarbarang['harga']) ?></td>
                            <td style="padding:5px;margin:5px"><?php echo $daftarbarang['jumlah'] ?></td>
                            <td style="padding:5px;margin:5px"><?php echo rupiah($daftarbarang['total']) ?></td>
                        </tr>
                    <?php
                        $nobelanja++;
                    } ?>
                    <tr>
                        <td colspan="4" style="text-align:right">Grand Total : &nbsp;</b></em></td>
                        <td class="text-success" style="padding:5px;margin:5px"><?php echo formatrupiah($pecah['total']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:right">Uang Pembeli : &nbsp;</b></em></td>
                        <td class="text-success" style="padding:5px;margin:5px"><?php echo formatrupiah($pecah['uangpembeli']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:right">Kembalian : &nbsp;</b></em></td>
                        <td class="text-success" style="padding:5px;margin:5px"><?php echo formatrupiah($pecah['kembalian']) ?></td>
                    </tr>
                </tbody>
            </table>
            <br><br>
            <table cellspacing='0' cellpadding='0' style='width:350px; font-size:12pt; font-family:calibri; border-collapse: collapse;' border='0'>
                <tr>
                    <td width="35"><br><br><br><br></td>
                    <?php
                    $now = date("Y-m-d");

                    ?>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;Penerima <br><br><br><br><br>(.....................)</td>
                    <td width="60"><br><br><br><br></td>
                    <?php
                    $now = date("Y-m-d");

                    ?>
                    <td>Hormat Kami, <br><br><br><br><br>(.....................)</td>
                </tr>
            </table>
        </div>
    </center>
</body>

</html>
<script>
    window.print();
</script>