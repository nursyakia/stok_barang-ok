<?php
require_once "lib/koneksi.php";

$penjualanID = $_GET['edit'] ?? null;
$pesan = "";

// Hapus transaksi
if (isset($_GET['hapus'])) {
    $hapusID = $_GET['hapus'];
    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT ProdukID, JumlahProduk FROM tbdetailpenjualan WHERE PenjualanID = ?");
        $stmt->execute([$hapusID]);
        foreach ($stmt as $row) {
            $conn->prepare("UPDATE tbproduk SET Stok = Stok + :jml WHERE ProdukID = :id")
                ->execute([':jml' => $row['JumlahProduk'], ':id' => $row['ProdukID']]);
        }

        $conn->prepare("DELETE FROM tbdetailpenjualan WHERE PenjualanID = ?")->execute([$hapusID]);
        $conn->prepare("DELETE FROM tbpenjualan WHERE PenjualanID = ?")->execute([$hapusID]);
        $conn->commit();
        $pesan = "✅ Transaksi berhasil dihapus!";
    } catch (PDOException $e) {
        $conn->rollBack();
        $pesan = "❌ Gagal menghapus transaksi: " . $e->getMessage();
    }
}

// Simpan / Edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pelanggan_id'])) {
    $pelangganID = $_POST['pelanggan_id'];
    $produkIDs = $_POST['produk_id'];
    $jumlahs = $_POST['jumlah'];
    $tanggal = date('Y-m-d');
    $totalHarga = 0;

    try {
        $conn->beginTransaction();

        if ($penjualanID) {
            // Kembalikan stok sebelum update
            $stmt = $conn->prepare("SELECT ProdukID, JumlahProduk FROM tbdetailpenjualan WHERE PenjualanID = ?");
            $stmt->execute([$penjualanID]);
            foreach ($stmt as $row) {
                $conn->prepare("UPDATE tbproduk SET Stok = Stok + :jml WHERE ProdukID = :id")
                    ->execute([':jml' => $row['JumlahProduk'], ':id' => $row['ProdukID']]);
            }

            $conn->prepare("DELETE FROM tbdetailpenjualan WHERE PenjualanID = ?")->execute([$penjualanID]);
            $conn->prepare("UPDATE tbpenjualan SET TanggalPenjualan = ?, PelangganID = ? WHERE PenjualanID = ?")
                ->execute([$tanggal, $pelangganID, $penjualanID]);
        } else {
            $stmt = $conn->prepare("INSERT INTO tbpenjualan (TanggalPenjualan, TotalHarga, PelangganID) VALUES (?, 0, ?)");
            $stmt->execute([$tanggal, $pelangganID]);
            $penjualanID = $conn->lastInsertId();
        }

        foreach ($produkIDs as $i => $produkID) {
            $jumlah = $jumlahs[$i];

            $stmt = $conn->prepare("SELECT Harga, Stok, NamaProduk FROM tbproduk WHERE ProdukID = ?");
            $stmt->execute([$produkID]);
            $produk = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$produk) throw new Exception("Produk tidak ditemukan.");
            if ($jumlah > $produk['Stok']) {
                throw new Exception("⚠️ Stok produk <strong>{$produk['NamaProduk']}</strong> tidak mencukupi. Tersedia: {$produk['Stok']}, diminta: {$jumlah}.");
            }

            $subtotal = $jumlah * $produk['Harga'];
            $totalHarga += $subtotal;

            $conn->prepare("INSERT INTO tbdetailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) VALUES (?, ?, ?, ?)")
                ->execute([$penjualanID, $produkID, $jumlah, $subtotal]);

            $conn->prepare("UPDATE tbproduk SET Stok = Stok - ? WHERE ProdukID = ?")->execute([$jumlah, $produkID]);
        }

        $conn->prepare("UPDATE tbpenjualan SET TotalHarga = ? WHERE PenjualanID = ?")
            ->execute([$totalHarga, $penjualanID]);

        $conn->commit();
        $pesan = "✅ Transaksi berhasil disimpan!";
        $penjualanID = null;
    } catch (Exception $e) {
        $conn->rollBack();
        $pesan = $e->getMessage();
    }
}
?>

<!-- HTML START -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-gradient bg-primary text-white rounded-top-4">
                    <h4 class="mb-0"><?= $penjualanID ? '✏️ Edit' : '📝 Input' ?> Transaksi & 📋 Riwayat Penjualan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- FORM TRANSAKSI -->
                        <div class="col-lg-12 mb-4">
                            <form method="POST">
                                <div class="row">
                                    <!-- Pilih Pelanggan -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Pilih Pelanggan</label>
                                        <select name="pelanggan_id" class="form-select" required>
                                            <option value="">-- Pilih Pelanggan --</option>
                                            <?php
                                            $pelanggan = $conn->query("SELECT * FROM tbpelanggan ORDER BY NamaPelanggan");
                                            foreach ($pelanggan as $row) {
                                                $selected = ($penjualanID && $conn->query("SELECT PelangganID FROM tbpenjualan WHERE PenjualanID = $penjualanID")->fetchColumn() == $row['PelangganID']) ? "selected" : "";
                                                echo "<option value='{$row['PelangganID']}' $selected>{$row['NamaPelanggan']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Produk dan Jumlah -->
                                    <div class="col-md-12 mb-3">
                                        <div id="produk-container">
                                            <?php
                                            $produkList = $conn->query("SELECT * FROM tbproduk ORDER BY NamaProduk");
                                            $produkOptions = "";
                                            foreach ($produkList as $p) {
                                                $produkOptions .= "<option value='{$p['ProdukID']}'>{$p['NamaProduk']} - Rp" . number_format($p['Harga'], 0, ',', '.') . " (Stok: {$p['Stok']})</option>";
                                            }

                                            if ($penjualanID) {
                                                $stmtDetail = $conn->prepare("SELECT * FROM tbdetailpenjualan WHERE PenjualanID = ?");
                                                $stmtDetail->execute([$penjualanID]);
                                                foreach ($stmtDetail as $detail) {
                                                    echo "<div class='produk-item mb-3 border p-3 bg-light rounded'>
                                                            <select name='produk_id[]' class='form-select mb-2' required>
                                                                <option value=''>-- Pilih Produk --</option>";
                                                    foreach ($conn->query("SELECT * FROM tbproduk ORDER BY NamaProduk") as $p) {
                                                        $selected = ($p['ProdukID'] == $detail['ProdukID']) ? "selected" : "";
                                                        echo "<option value='{$p['ProdukID']}' $selected>{$p['NamaProduk']} - Rp" . number_format($p['Harga'], 0, ',', '.') . " (Stok: {$p['Stok']})</option>";
                                                    }
                                                    echo "</select>
                                                            <input type='number' name='jumlah[]' class='form-control' value='{$detail['JumlahProduk']}' min='1' required>
                                                        </div>";
                                                }
                                            } else {
                                                echo "<div class='produk-item mb-3 border p-3 bg-light rounded'>
                                                        <select name='produk_id[]' class='form-select mb-2' required>
                                                            <option value=''>-- Pilih Produk --</option>
                                                            $produkOptions
                                                        </select>
                                                        <input type='number' name='jumlah[]' class='form-control' placeholder='Jumlah' min='1' required>
                                                    </div>";
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Button Tambah Produk dan Simpan -->
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-outline-success btn-sm mb-3" onclick="tambahProduk()">+ Tambah Produk</button>
                                        <button type="submit" class="btn btn-primary w-100"><?= $penjualanID ? '💾 Update' : '💾 Simpan' ?> Transaksi</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Riwayat Transaksi -->
                        <div class="col-md-12">
                            <div class="table-responsive" style="max-height: 450px;">
                                <table class="table table-hover table-bordered table-striped align-middle text-center table-sm">
                                    <thead class="table-success">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "SELECT p.PenjualanID, p.TanggalPenjualan, pel.NamaPelanggan, p.TotalHarga
                                                FROM tbpenjualan p
                                                JOIN tbpelanggan pel ON p.PelangganID = pel.PelangganID
                                                ORDER BY p.PenjualanID DESC";
                                        $stmt = $conn->query($sql);
                                        foreach ($stmt as $row) {
                                            $total = number_format($row['TotalHarga'], 0, ',', '.');
                                            echo "<tr>
                                                    <td>{$no}</td>
                                                    <td>{$row['TanggalPenjualan']}</td>
                                                    <td class='text-start'>{$row['NamaPelanggan']}</td>
                                                    <td class='text-end'><span class='badge bg-success'>Rp {$total}</span></td>
                                                    <td>
                                                        <a href='?page=transaksi&edit={$row['PenjualanID']}' class='btn btn-sm btn-warning'>✏️</a>
                                                        <a href='?page=transaksi&hapus={$row['PenjualanID']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus transaksi ini?')\">🗑️</a>
                                                    </td>
                                                </tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function tambahProduk() {
    const container = document.getElementById('produk-container');
    const produkItem = document.querySelector('.produk-item');
    const clone = produkItem.cloneNode(true);
    clone.querySelector("select").selectedIndex = 0;
    clone.querySelector("input").value = "";
    container.appendChild(clone);
}
</script>
