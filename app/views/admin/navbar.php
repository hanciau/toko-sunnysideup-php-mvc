<div class="row">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <!-- Bagian Pertama: Logo -->
            <div class="navbar-brand">
                <a href="<?= BASEURL; ?>/admin/about">
                    <img src="<?= BASEURL; ?>/image/sunnylogo.png" width="146" height="72" alt="Logo">
                </a>
            </div>
            <div class="navbar-nav">
                    <a class="nav-item nav-link" href="<?= BASEURL; ?>/admin/hapus_product">Katalog</a>
                    <a href='https://api.whatsapp.com/send?phone=6282165392323&text=Halo,saya ingin custom produk.' class="nav-item nav-link text-dark">
                        Custom Produk
                    </a>
                </div>
            <!-- Bagian Kedua: Search Bar -->
            <div class="collapse navbar-collapse justify-content-center">
                <form class="d-flex" role="search" action="<?= BASEURL; ?>/admin/hapus_product" method="POST">
                    <input class="form-control me-2 text-center" type="search" placeholder="Search" aria-label="Search" name="keyword">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>

            <!-- Bagian Ketiga: Item Navigasi -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">

                <?php if (!empty($data['profile'])) : ?>
                    <div class="nav-item d-flex align-items-center">
                        <a href='<?= BASEURL; ?>/profile' class='nav-link text-dark d-flex align-items-center'>
                            <span><?= $data['profile']['real_name'] ?></span>
                        </a>
                        <a href='<?= BASEURL; ?>/admin/profile' class='nav-link text-dark d-flex'>
                            <img src='data:image/jpeg;base64,<?= base64_encode($data['profile']['image']) ?>' class="utama-profile-image rounded-circle ml-2" alt="Profile Image" style="width: 40px; height: 40px; margin-top:17px;" />
                        </a>
                        <a href='<?= BASEURL; ?>/admin/logout' class='text-decoration-none text-dark'>Logout</a>
                </div>
                <?php else : ?>
                    <div class='nav-item'>
                    <a href='<?= BASEURL; ?>/admin/login' class='text-decoration-none text-dark'>Login/Sign Up</a>
                    </div>
                <?php endif; ?>
                <a href='<?= BASEURL; ?>/keranjang' class="nav-link text-dark ml-2">
                    <img src='<?= BASEURL ?>/image/pngegg.png' width='30' height='30' alt='Shopping Cart Icon' />
                </a>
            </div>
        </nav>
    </div>
</div>
