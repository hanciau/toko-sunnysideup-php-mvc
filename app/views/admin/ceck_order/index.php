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
                <?php function renderTable($orders, $status)
                { ?>
                    <h2>Pesanan dengan status: <?= $status ?></h2>
                    <?php if (count($orders) > 0) : ?>
                        <table border="1">
                            <tr>
                                <th>order_date</th>
                                <th>total_harga</th>
                                <th>waktu</th>
                                <th>statuspesanan</th>
                                <th>metode_pengiriman</th>
                                <th>metode_pembayaran</th>
                                <th>ongkos_kirim</th>
                                <th>total_biaya</th>
                                <th>perusahaan_pengiriman</th>
                                <th>detail</th>
                            </tr>
                            <?php foreach ($orders as $order) : ?>
                                <tr>
                                    <td><?= $order["order_date"] ?></td>
                                    <td><?= $order["total_harga"] ?></td>
                                    <td><?= $order["waktu"] ?></td>
                                    <td><?= $order["statuspesanan"] ?></td>
                                    <td><?= $order["metode_pengiriman"] ?></td>
                                    <td><?= $order["metode_pembayaran"] ?></td>
                                    <td><?= $order["ongkos_kirim"] ?></td>
                                    <td><?= $order["total_biaya"] ?></td>
                                    <td><?= $order["perusahaan_pengiriman"] ?></td>
                                    <td><a type="button" class="btn btn-danger" href="<?= BASEURL ?>/admin/detail_order/<?= $order["order_id"] ?>">detail</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else : ?>
                        <p>Tidak ada data yang tersedia.</p>
                    <?php endif; ?>
                <?php } ?>
                <?php renderTable($orders['pending'], 'pending'); ?>
                <?php renderTable($orders['expired'], 'expired'); ?>
                <?php renderTable($orders['sukses'], 'sukses'); ?>
            </div>

        </main>
    </div>
</div>