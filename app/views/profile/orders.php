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
<div class="profil-container rounded-5">
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <h3>Lihat Pesanan :</h3>
                <hr>
                <br>
                <div class="col-md-6 content-menu" style="margin-top:-20px;">
                    <table class="table table-striped" style="text-align: left;">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Tanggal Order</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data['orders'] as $order) {
                                echo "<tr>";
                                echo "<td>{$order['order_id']}</td>";
                                echo "<td>{$order['order_date']}</td>";
                                echo "<td>{$order['total_biaya']}</td>";
                                echo "<td>{$order['statuspesanan']}</td>";
                                echo "<td>";

                                if ($order['statuspesanan'] == 'pending') {
                                    echo "<a type='button' class='btn btn-primary' href='" . BASEURL . "/order/payment/" . $order['order_id'] . "'>Bayar</a>";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>