<?php
require_once "lib/koneksi.php";

// Ambil semua data penjualan
$dataPenjualan = $conn->query("
    SELECT p.TanggalPenjualan, pel.NamaPelanggan, p.TotalHarga
    FROM tbpenjualan p
    JOIN tbpelanggan pel ON p.PelangganID = pel.PelangganID
    ORDER BY p.TanggalPenjualan DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua data produk
$dataProduk = $conn->query("SELECT * FROM tbproduk ORDER BY NamaProduk")->fetchAll(PDO::FETCH_ASSOC);

// Hitung total keseluruhan
$totalKeseluruhan = array_sum(array_column($dataPenjualan, 'TotalHarga'));

// Tanggal laporan
$tanggalLaporan = date("d F Y");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Penjualan & Stok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #e6f0ff;
      font-family: 'Segoe UI', sans-serif;
    }
    .laporan {
      background: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      margin-top: 50px;
      position: relative;
    }
    .header {
      border-bottom: 3px solid #007bff;
      margin-bottom: 20px;
      padding-bottom: 10px;
      text-align: center;
    }
    .header h2 {
      color: #007bff;
      font-weight: 700;
      text-transform: uppercase;
    }
    .table th {
      background-color: #007bff;
      color: #fff;
      text-align: center;
    }
    .btn-cetak {
      position: absolute;
      top: 20px;
      left: 30px;
    }
    @media print {
      .btn-cetak {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="container laporan">
  <div class="btn-cetak">
    <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak Laporan</button>
  </div>

  <div class="header">
    <h2>Laporan Penjualan</h2>
    <p>Tanggal: <?= $tanggalLaporan ?></p>
  </div>

  <?php if (count($dataPenjualan) > 0): ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($dataPenjualan as $i => $row): ?>
      <tr>
        <td class="text-center"><?= $i + 1 ?></td>
        <td><?= $row['TanggalPenjualan'] ?></td>
        <td><?= $row['NamaPelanggan'] ?></td>
        <td>Rp <?= number_format($row['TotalHarga'], 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3" class="text-end fw-bold">Total Keseluruhan</td>
        <td class="fw-bold">Rp <?= number_format($totalKeseluruhan, 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>
  <?php else: ?>
    <div class="alert alert-warning text-center">Tidak ada transaksi penjualan yang tercatat.</div>
  <?php endif; ?>
</div>

<div class="container laporan">
  <div class="header">
    <h2>Laporan Stok Barang</h2>
    <p>Tanggal: <?= $tanggalLaporan ?></p>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($dataProduk as $i => $produk): 
        $stok = $produk['Stok'];
        if ($stok >= 3) {
          $badge = "bg-success";
          $keterangan = "Stok Aman";
        } elseif ($stok == 2) {
          $badge = "bg-warning text-dark";
          $keterangan = "Stok Menipis";
        } else {
          $badge = "bg-danger";
          $keterangan = "Stok Hampir Habis";
        }
      ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= $produk['NamaProduk'] ?></td>
          <td>Rp <?= number_format($produk['Harga'], 0, ',', '.') ?></td>
          <td><span class="badge <?= $badge ?>"><?= $stok ?></span></td>
          <td><?= $keterangan ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
