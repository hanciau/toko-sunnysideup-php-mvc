<div class="container-fluid" style="margin-top: 110px;">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar shadow position-fixed" style="height: 100%"'>
            <div class="position-sticky">
                <ul class="nav flex-column">
                <li class="nav-item">
                        <a class="nav-link active" href="<?= BASEURL; ?>/profile/profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASEURL; ?>/profile/orders">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASEURL; ?>/profile/alamat">Alamat</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 " id="main-content">
            <div class="profil-container rounded-5" style="height: 100%;">
            <?php Flasher::flashprofile(); ?>
                <div>
                    <div class="row">
                        <div>
                                <div class="container position-relative">
                                <h1>Profile</h1>
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#profileModal">

                                    <?php

                                    if (!empty($data['profile']['image'])) {
                                        echo '<img src="data:image/jpeg;base64,' . base64_encode($data['profile']['image']) . '" class="utama-profile-image" alt="Profile Image" />';
                                    } else {
                                        echo '<img src="' . BASEURL . '/image/image.png" class="utama-profile-image" alt="Profile Image" />';
                                    }
                                    ?>

                                </button>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="profileModalLabel">Tambah/Ubah Profile</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="profileForm" action="<?= BASEURL ?>/profile/profileimagebaru" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="profileImage">Pilih Gambar</label>
                                                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                                                </div>
                                                <div class="form-group">
                                                    <img id="imagePreview" src="#" alt="Preview Gambar" style="display: none; max-width: 100%; height: auto;">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3>Profile :</h3>
                            <hr>
                            <br>
                            <div style="margin-top:-20px;">
                                <form action="<?= BASEURL; ?>/profile/editprofile" method="post">
                                    <table class="table table-striped" style="text-align: left;">
                                        <tr>
                                            <td>Nama</td>
                                            <td>:</td>
                                            <td><input type="text" name="real_name" value="<?php echo $data['profile']['real_name']; ?>" required></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>:</td>
                                            <td><input type="email" name="email" value="<?php echo $data['profile']['email']; ?>" required></td>
                                        </tr>
                                        <tr>
                                            <td>Telephone</td>
                                            <td>:</td>
                                            <td><input type="text" name="telephone" value="<?php echo $data['profile']['telephone']; ?>" required></td>
                                            </td>
                                        </tr>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Save Profile</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>