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
        <main class="col-md-9 ms-sm-auto col-lg-10" id="main-content">
            <div class="profil-container rounded-5 p-3 bg-white shadow" style="height: 100%;">
                <?php
                $customer_data = $data['orders']['customer_data'];
                $alamat = $data['orders']['alamat'];
                $detail_pesanan = $data['orders']['order_data'];
                $item_details = $data['orders']['item_details'];
                ?>
                
                <!-- Customer Data Table -->
                <h3>Identitas Customer</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td><?= $customer_data['realnama'] ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $customer_data['email'] ?></td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td><?= $customer_data['telephone'] ?></td>
                    </tr>
                </table>

                <!-- Alamat Table -->
                <h3>Alamat Pengiriman</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>Alamat</th>
                        <td><?= $alamat['address'] ?></td>
                    </tr>
                    <tr>
                        <th>Kota</th>
                        <td><?= $alamat['kota'] ?></td>
                    </tr>
                    <tr>
                        <th>Provinsi</th>
                        <td><?= $alamat['provinsi'] ?></td>
                    </tr>
                    <tr>
                        <th>Kode Pos</th>
                        <td><?= $alamat['postal_code'] ?></td>
                    </tr>
                </table>

                <!-- Order Data Table -->
                <h3>Detail Pesanan</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>ID Pesanan</th>
                        <td><?= $detail_pesanan['order_id'] ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pesanan</th>
                        <td><?= $detail_pesanan['order_date'] ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= $detail_pesanan['statuspesanan'] ?></td>
                    </tr>
                    <tr>
                        <th>Metode Pengiriman</th>
                        <td><?= $detail_pesanan['metode_pengiriman'] ?></td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td><?= $detail_pesanan['metode_pembayaran'] ?></td>
                    </tr>
                    <tr>
                        <th>Ongkos Kirim</th>
                        <td><?= number_format($detail_pesanan['ongkos_kirim'], 2) ?></td>
                    </tr>
                    <tr>
                        <th>Total Biaya</th>
                        <td><?= number_format($detail_pesanan['total_biaya'], 2) ?></td>
                    </tr>
                </table>

                <!-- Item Details Table -->
                <h3>Detail Barang</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Barang</th>
                            <th>Kuantitas</th>
                            <th>Harga</th>
                            <th>Berat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_weight = 0;
                        foreach ($item_details as $item) : 
                            $item_weight = $item['quantity'] * $item['berat'];
                            $total_weight += $item_weight;
                        ?>
                            <tr>
                                <td><img src="data:image/jpeg;base64,<?= base64_encode($item['image_url']) ?>" alt="<?= $item['name'] ?>" width="50"></td>
                                <td><?= $item['name'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['harga_seluruh'], 2) ?></td>
                                <td><?= $item_weight ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="4" style="text-align: right;">Total Berat:</td>
                            <td><?= $total_weight ?></td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </main>
    </div>
</div>