<?php
require_once "lib/koneksi.php";

$pesan = "";
$dataBaru = [];
$showCard = false;
$nama = $alamat = $nomor = "";
$isEdit = false;
$idEdit = "";

if (isset($_GET['hapus'])) {
    $idHapus = $_GET['hapus'];
    try {
        $stmt = $conn->prepare("DELETE FROM tbpelanggan WHERE PelangganID = :id");
        $stmt->bindParam(':id', $idHapus);
        $stmt->execute();
        $pesan = "Data berhasil dihapus.";
    } catch (PDOException $e) {
        $pesan = "Gagal menghapus data: " . $e->getMessage();
    }
}

if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $isEdit = true;
    try {
        $stmt = $conn->prepare("SELECT * FROM tbpelanggan WHERE PelangganID = :id");
        $stmt->bindParam(':id', $idEdit);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $nama = $row['NamaPelanggan'];
            $alamat = $row['Alamat'];
            $nomor = $row['Nomor'];
        } else {
            $pesan = "Data tidak ditemukan untuk diedit.";
        }
    } catch (PDOException $e) {
        $pesan = "Gagal mengambil data: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $nomor = htmlspecialchars(trim($_POST['nomor']));
    $idForm = $_POST['id_pelanggan'];

    if (empty($nama) || empty($alamat) || empty($nomor)) {
        $pesan = "Semua field harus diisi!";
    } else {
        try {
            if (!empty($idForm)) {
                $stmt = $conn->prepare("UPDATE tbpelanggan SET NamaPelanggan = :nama, Alamat = :alamat, Nomor = :nomor WHERE PelangganID = :id");
                $stmt->bindParam(':nama', $nama);
                $stmt->bindParam(':alamat', $alamat);
                $stmt->bindParam(':nomor', $nomor);
                $stmt->bindParam(':id', $idForm);
                $stmt->execute();
                $pesan = "Data pelanggan berhasil diperbarui!";
            } else {
                $stmt = $conn->prepare("INSERT INTO tbpelanggan (NamaPelanggan, Alamat, Nomor) VALUES (:nama, :alamat, :nomor)");
                $stmt->bindParam(':nama', $nama);
                $stmt->bindParam(':alamat', $alamat);
                $stmt->bindParam(':nomor', $nomor);
                $stmt->execute();
                $pesan = "Data pelanggan berhasil disimpan!";
                $showCard = true;
                $dataBaru = ['nama' => $nama, 'alamat' => $alamat, 'nomor' => $nomor];
            }

            $nama = $alamat = $nomor = "";
            $isEdit = false;
        } catch (PDOException $e) {
            $pesan = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}

$dataPelanggan = [];
try {
    $sql = "SELECT * FROM tbpelanggan ORDER BY PelangganID DESC";
    $stmt = $conn->query($sql);
    $dataPelanggan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pesan .= "<br>Gagal mengambil data pelanggan: " . $e->getMessage();
}
?>

<style>
    body {
        background-color: #f1f3f5;
    }

    input:focus {
        border-color: #0077b6 !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 119, 182, 0.15) !important;
    }

    .card {
        border-radius: 1.5rem !important;
        width: 1100px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        background-color: #fff;
    }

    .tabel-container {
        border-radius: 1rem;
        overflow: auto;
        max-height: 400px;
    }

    .tabel-custom {
        min-width: 600px;
    }

    .tabel-custom th, .tabel-custom td {
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Edit Button (Kuning) */
    .btn-warning {
        background-color: #ffcc00 !important;
        border-color: #ffcc00 !important;
        color: #fff !important;
    }

    /* Hapus Button (Merah) */
    .btn-danger {
        background-color: #ff4d4d !important;
        border-color: #ff4d4d !important;
        color: #fff !important;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container" style="margin-top: 100px;">
    <div class="p-4 rounded-4 shadow-sm text-white" style="background: linear-gradient(135deg, #0077b6, #90e0ef);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">🎯 Manajemen Data Pelanggan</h2>
                <p class="mb-0">Kelola data pelanggan secara efisien dan mudah melalui form dan daftar interaktif di bawah ini.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4" style="margin-top: 50px;">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center p-3 shadow border-start border-5 border-info bg-white rounded-4">
            <div class="bg-info text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 55px; height: 55px;">
                <i class="bi bi-people-fill" style="font-size: 1.6rem;"></i>
            </div>
            <div>
                <h6 class="mb-0 text-muted">Total Pelanggan</h6>
                <h4 class="mb-0 fw-bold"><?= count($dataPelanggan); ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center p-3 shadow border-start border-5 border-info bg-white rounded-4">
            <div class="bg-info text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 55px; height: 55px;">
                <i class="bi bi-pencil-square" style="font-size: 1.6rem;"></i>
            </div>
            <div>
                <h6 class="mb-0 text-muted">Status Form</h6>
                <h4 class="mb-0 fw-bold"><?= $isEdit ? 'Edit Mode' : 'Tambah Baru'; ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center p-3 shadow border-start border-5 border-info bg-white rounded-4">
            <div class="bg-info text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 55px; height: 55px;">
                <i class="bi bi-calendar-event-fill" style="font-size: 1.6rem;"></i>
            </div>
            <div>
                <h6 class="mb-0 text-muted">Tanggal Hari Ini</h6>
                <h4 class="mb-0 fw-bold"><?= date('d M Y'); ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 20px;">
    <div class="card shadow-lg col-lg-10 mx-auto border-0 rounded-4">
        <div class="card-header bg-primary text-white text-center rounded-top-4">
            <h4 class="mb-0">Form Input & Daftar Pelanggan</h4>
        </div>

        <div class="card-body bg-light rounded-bottom-4 pb-2">
            <?php if (!empty($pesan)) : ?>
                <div id="alertPesan" class="position-relative">
                    <div class="alert alert-info border-0 d-flex align-items-center shadow-sm fade show" role="alert">
                        <i class="bi bi-info-circle-fill fs-2 text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block text-primary mb-1">Info</strong>
                            <div class="text-dark small"><?php echo $pesan; ?></div>
                        </div>
                        <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <h5 class="text-center mb-3 fw-semibold"><?php echo $isEdit ? 'Edit Pelanggan' : 'Inputan Pelanggan'; ?></h5>
                    <form method="POST" class="bg-white p-4 rounded-4 shadow-sm border border-2" style="background: linear-gradient(135deg, #f1f9ff, #ffffff);">
                        <input type="hidden" name="id_pelanggan" value="<?php echo $isEdit ? htmlspecialchars($idEdit) : ''; ?>">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold text-primary">
                                <i class="bi bi-person-fill me-2"></i>Nama Pelanggan
                            </label>
                            <input type="text" id="nama" name="nama" class="form-control rounded-4 border-2 shadow-sm" value="<?php echo htmlspecialchars($nama); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-semibold text-primary">
                                <i class="bi bi-geo-alt-fill me-2"></i>Alamat
                            </label>
                            <input type="text" id="alamat" name="alamat" class="form-control rounded-4 border-2 shadow-sm" value="<?php echo htmlspecialchars($alamat); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label for="nomor" class="form-label fw-semibold text-primary">
                                <i class="bi bi-telephone-fill me-2"></i>Nomor Telepon
                            </label>
                            <input type="text" id="nomor" name="nomor" class="form-control rounded-4 border-2 shadow-sm" value="<?php echo htmlspecialchars($nomor); ?>" required>
                        </div>
                        <button type="submit" class="btn w-100 rounded-4 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, <?= $isEdit ? '#ffc107, #ffe082' : '#0077b6, #90e0ef' ?>); border: none;">
                            <i class="bi <?= $isEdit ? 'bi-pencil-square' : 'bi-save' ?> me-2"></i><?= $isEdit ? 'Perbarui' : 'Simpan'; ?>
                        </button>
                    </form>
                </div>

                <div class="col-md-7">
                    <?php if (!empty($dataPelanggan)) : ?>
                        <h5 class="text-center mb-3 fw-semibold">Daftar Pelanggan</h5>
                        <div class="table-responsive bg-white p-3 rounded-4 shadow-sm">
                            <table class="table table-bordered table-sm table-striped table-hover mb-0 tabel-custom">
                                <thead class="table-primary text-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Nomor</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataPelanggan as $index => $pelanggan) : ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($pelanggan['NamaPelanggan']); ?></td>
                                            <td><?php echo htmlspecialchars($pelanggan['Alamat']); ?></td>
                                            <td><?php echo htmlspecialchars($pelanggan['Nomor']); ?></td>
                                            <td>
                                                <a href="?page=pelanggan&edit=<?php echo $pelanggan['PelangganID']; ?>" class="btn btn-warning btn-sm me-1">
                                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                                </a>
                                                <a href="?page=pelanggan&hapus=<?php echo $pelanggan['PelangganID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="bi bi-trash-fill me-1"></i>Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <p class="text-center text-muted mt-4">Belum ada data pelanggan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
