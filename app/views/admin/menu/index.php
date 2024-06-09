<div class="container-fluid" style="margin-top: 110px;">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar shadow position-fixed" style="height: 100%">
            <div class="position-sticky">
                <ul class="nav flex-column">
                <li class="nav-item">
                        <a class="nav-link active" href="<?= BASEURL; ?>/admin/profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASEURL; ?>/admin/menu">menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASEURL; ?>/admin/logout">logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 " id="main-content">
            <div class="profil-container rounded-5" style="height: 100%;">
            <header>
                <h1>Admin Dashboard</h1>
            </header>
            <div class="container">
                <a href="<?= BASEURL; ?>/logout" class="box">
                    <h2>keluar</h2>
                </a>
                <a href="<?= BASEURL; ?>/admin/insert_product" class="box">
                    <h2>Tambah Produk</h2>
                </a>
                <a href="<?= BASEURL; ?>/admin/daftarorderan" class="box">
                    <h2>Orderan</h2>
                </a>
                <a href="<?= BASEURL; ?>/admin/hapus_product" class="box">
                    <h2>Hapus Produk</h2>
                </a>
            </div>
            </div>
            </div>
        </main>
    </div>
</div><div style="background-color: grey;">