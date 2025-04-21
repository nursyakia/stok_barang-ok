<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['user'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard - stockcount</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/stokbarang.min.css" />
 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
  <style>

    body {
      font-family: 'Segoe UI', sans-serif;
      overflow-x: hidden; /* Hapus scroll horizontal */
    }
    .card {
      margin-top: 80px;
      border-radius: 20px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .card-header {
      background-color: #ffffff;
      border-radius: 20px 20px 0 0;
      text-align: center;
      padding: 25px;
    }
    .card-header h2 {
      font-weight: bold;
      color: #0072ff;
    }
    .btn-primary {
      background-color: #0072ff;
      border: none;
      font-weight: bold;
    }
    .btn-primary:hover {
      background-color: #005fd4;
    }
    .sidebar-wrapper {
  max-height: 100vh;
  overflow-y: auto;
}
.main {
  min-height: 85vh;
}
  /* Dropdown width customization */
  .dropdown-menu.messages-notif-box,
  .dropdown-menu.notif-box,
  .dropdown-menu.dropdown-user {
    width: 200px !important; /* Ubah sesuai kebutuhan, misalnya 280px atau 250px */
  }

  /* Optional: Sesuaikan juga bagian scroll agar tidak terlalu besar */
  .notif-scroll.scrollbar-outer,
  .message-notif-scroll.scrollbar-outer,
  .dropdown-user-scroll.scrollbar-outer {
    max-height: 300px; /* Batas tinggi jika isi terlalu panjang */
    overflow-y: auto;
  }

  /* Avatar dan teks agar tidak terlalu besar */
  .notif-img img,
  .avatar-img {
    width: 40px;
    height: 40px;
    object-fit: cover;
  }

  .notif-content .block,
  .notif-content .subject {
    font-size: 14px;
  }

  .notif-content .time {
    font-size: 12px;
    color: gray;
  }
  .navbar {
  padding: 0.3rem 1rem !important;
}

.navbar .nav-link,
.navbar .dropdown-toggle {
  font-size: 14px;
  padding: 6px 10px;
}

.navbar .avatar-sm img {
  width: 30px;
  height: 30px;
}

.profile-username {
  font-size: 14px;
  margin-left: 5px;
}

.input-group .form-control {
  height: 32px;
  font-size: 14px;
}

  </style>

    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="admin.php" class="logo">
              <img
                src="assets/img/logoww.png"
                alt="navbar brand"
                class="navbar-brand"
                height="140"
                width="170"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
            <li class="nav-item active">
              <a href="admin.php">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
                <div class="collapse" id="dashboard">
                </div>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Main Menu</h4>
              </li>
              <li class="nav-item">
                <a href="?page=pelanggan">
                  <i class="fas fa-users"></i> <!-- Ikon untuk Pelanggan -->
                  <p>Data Pelanggan</p>
                </a>
             </li>
             <li class="nav-item">
               <a href="?page=dataproduk">
                 <i class="fas fa-box-open"></i> <!-- Ikon untuk Produk -->
                 <p>Data Produk</p>
               </a>
              </li>
              <li class="nav-item">
                <a href="?page=transaksi">
                  <i class="fas fa-shopping-cart"></i> <!-- Ikon untuk Transaksi -->
                  <p>Transaksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=datapetugas">
                  <i class="fas fa-chart-line"></i> <!-- Ikon untuk Laporan Penjualan -->
                  <p>Data Petugas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=laporanpenjualan">
                  <i class="fas fa-chart-line"></i> <!-- Ikon untuk Laporan Penjualan -->
                  <p>Laporan Penjualan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="logout.php">
                  <i class="fas fa-sign-out-alt"></i> <!-- Ikon untuk Laporan Penjualan -->
                  <p>logout</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->


      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="admin.php" class="logo">
                <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input
                    type="text"
                    placeholder="Search ..."
                    class="form-control"
                  />
                </div>
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Search ..."
                          class="form-control"
                        />
                      </div>
                    </form>
                  </ul>
                </li>           

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="assets/img/admin.jpg"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Admin</span>
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>
        <main>
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case "pelanggan":
                    include "datapelanggan.php";
                    break;
                case "dataproduk":
                    include "dataproduk.php";
                    break;
                case "transaksi":
                    include "transaksi.php";
                    break;
                 case "datapetugas":
                    include "datapetugas.php";
                    break;
                case "laporanpenjualan":
                    include "laporanpenjualan.php";
                    break;
                case "update":
                    include "update_pengguna.php";
                    break;
                case "denda":
                     include "denda.php";
                     break;
                case "minjam":
                    include "minjam2.php";
                    break;
                default:
                include "datapelanggan.php";
                    break;
            }
          }
          else {
            include "datapelanggan.php";
          }
        ?>
          <!-- Bootstrap JS (Opsional, untuk fitur seperti dropdown/modal) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

   <!-- Google Maps Plugin -->
<script src="assets/js/plugin/gmaps/gmaps.js"></script>

<?php if (ob_get_level() > 0) ob_end_flush(); ?>
</body>
</html>
