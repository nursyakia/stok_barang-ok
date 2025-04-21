<?php
include 'lib/koneksi.php';

$pesan = "";
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
$edit_mode = false;
$produk_edit = [
    'ProdukID' => '',
    'NamaProduk' => '',
    'Harga' => '',
    'Stok' => '',
    'gambar' => ''
];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    try {
        $stmt = $conn->prepare("SELECT * FROM tbproduk WHERE ProdukID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $produk_edit = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$produk_edit) {
            $pesan = "Produk tidak ditemukan.";
            $edit_mode = false;
        }
    } catch (PDOException $e) {
        $pesan = "Gagal mengambil data produk: " . $e->getMessage();
    }
}

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = htmlspecialchars(trim($_POST['nama']));
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if ($_FILES['gambar']['name']) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $folder = "uploads/" . $gambar;
        move_uploaded_file($tmp, $folder);
    } else {
        $gambar = $_POST['gambar_lama'] ?? '';
    }

    try {
        if (!empty($id)) {
            $stmt = $conn->prepare("UPDATE tbproduk SET NamaProduk = :nama, Harga = :harga, Stok = :stok, gambar = :gambar WHERE ProdukID = :id");
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':harga', $harga);
            $stmt->bindParam(':stok', $stok);
            $stmt->bindParam(':gambar', $gambar);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $_SESSION['flash'] = "Produk berhasil diperbarui.";
        } else {
            $stmt = $conn->prepare("INSERT INTO tbproduk (NamaProduk, Harga, Stok, gambar) VALUES (:nama, :harga, :stok, :gambar)");
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':harga', $harga);
            $stmt->bindParam(':stok', $stok);
            $stmt->bindParam(':gambar', $gambar);
            $stmt->execute();
            $_SESSION['flash'] = "Produk berhasil ditambahkan.";
        }
    } catch (PDOException $e) {
        $pesan = "Gagal menyimpan produk: " . $e->getMessage();
    }
}

try {
    $stmt = $conn->query("SELECT * FROM tbproduk ORDER BY ProdukID DESC");
    $produk = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_produk = count($produk);
    $total_stok = array_sum(array_column($produk, 'Stok'));
    $produk_habis = count(array_filter($produk, function ($item) {
        return $item['Stok'] == 0;
    }));
} catch (PDOException $e) {
    $pesan = "Gagal mengambil data produk: " . $e->getMessage();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body {
        background: #f6f8fc;
    }
    .stat-card {
        transition: all 0.3s ease;
        border: none;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    .form-control {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-radius: 0.5rem;
    }
    .table-bordered-blue thead th,
    .table-bordered-blue tbody td {
        border: 1px solid #cfe2ff;
    }
    .table-bordered-blue {
        border: 1px solid #cfe2ff;
    }
    .rounded-circle.icon-box {
        width: 55px;
        height: 55px;
        background: linear-gradient(145deg, #007bff, #0056b3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.6rem;
    }
</style>

<div class="container" style="margin-top: 110px; max-width: 1100px;">
    <?php if ($pesan): ?>
        <div class="alert alert-danger mt-4"> <?= $pesan ?> </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card p-3 shadow bg-white rounded-4 d-flex align-items-center">
                <div class="rounded-circle icon-box me-3">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Total Produk</h6>
                    <h4 class="fw-bold mb-0"> <?= $total_produk ?> </h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-3 shadow bg-white rounded-4 d-flex align-items-center">
                <div class="rounded-circle icon-box me-3">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Total Stok</h6>
                    <h4 class="fw-bold mb-0"> <?= $total_stok ?> </h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-3 shadow bg-white rounded-4 d-flex align-items-center">
                <div class="rounded-circle icon-box me-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Produk Habis</h6>
                    <h4 class="fw-bold mb-0"> <?= $produk_habis ?> </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-5">
        <div class="card-header bg-primary text-white d-flex align-items-center gap-3">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-box-seam text-primary" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h5 class="mb-0">Manajemen Produk</h5>
                <small class="text-white-50">Tambah dan ubah data produk</small>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <?php if ($flash): ?>
                    <div class="alert alert-success"> <?= $flash ?> </div>
                <?php endif; ?>

                <input type="hidden" name="id" value="<?= $produk_edit['ProdukID'] ?>">
                <input type="hidden" name="gambar_lama" value="<?= $produk_edit['gambar'] ?>">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-dark">Nama Produk</label>
                        <input type="text" name="nama" class="form-control" value="<?= $produk_edit['NamaProduk'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-dark">Harga</label>
                        <input type="number" name="harga" class="form-control" value="<?= $produk_edit['Harga'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-dark">Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $produk_edit['Stok'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark">Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control">
                        <?php if ($edit_mode && $produk_edit['gambar']): ?>
                            <img src="uploads/<?= $produk_edit['gambar'] ?>" width="80" class="mt-2 rounded shadow-sm d-block">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" name="simpan" class="btn btn-primary w-100">
                            <?= $edit_mode ? 'Update Produk' : 'Simpan Produk' ?>
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0 table-bordered-blue">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($produk as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="text-start"> <?= $row['NamaProduk'] ?> </td>
                                <td>Rp <?= number_format($row['Harga']) ?> </td>
                                <td>
                                    <span class="badge bg-<?= $row['Stok'] == 0 ? 'danger' : 'success' ?>">
                                        <?= $row['Stok'] == 0 ? 'Habis' : $row['Stok'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['gambar']): ?>
                                        <img src="uploads/<?= $row['gambar'] ?>" width="60" class="rounded shadow-sm">
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="?page=dataproduk&edit=<?= $row['ProdukID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php if (count($produk) === 0): ?>
                            <tr>
                                <td colspan="6" class="text-muted">Belum ada produk.</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
