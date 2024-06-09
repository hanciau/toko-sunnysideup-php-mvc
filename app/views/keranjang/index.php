<div class="main-container" style="margin-top: 30px;">
    <div class="container p-4 mb-4 mt-4 rounded-5">
        <h2>Isi Keranjang</h2>
        <?php Flasher::flashapus(); ?>
        <form action="<?= BASEURL; ?>/order/index" method="post" id="orderForm">
            <table>
                <tr>
                    <th style="text-align: center; vertical-align: middle;">
                        <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes()">Pilih semua
                    </th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($data['keranjang'] as $k) : ?>
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">
                            <input type="checkbox" name="selected_items[]" value="<?= $k['id'] ?>">
                        </td>
                        <td>
                            <div style="display: flex; align-items: flex-start;">
                                <img src="data:image/jpeg;base64,<?= base64_encode($k['image_url']) ?>" alt="Product Image" style="margin-right: 10px; max-width: 100px; max-height: 100px;" />
                                <h3><?= $k['name'] ?></h3>
                            </div>
                        </td>
                        <td>
                            <p>Rp<?= number_format($k['price'], 2) ?></p>
                        </td>
                        <td>
                            <?php
                            if ($k['quantity'] > $k['stock']) {
                                echo '<p style="color: red;">' . $k['quantity'] . ' (Exceeds Stock)</p>';
                            } else {
                                echo '<p>' . $k['quantity'] . '</p>';
                            }
                            ?>
                        </td>
                        <td>Rp<?= number_format($k['harga_seluruh'], 2) ?></td>
                        <td>
                            <a type="button" class="btn btn-danger" href='<?= BASEURL; ?>/keranjang/hapus/<?= $k['id'] ?>' onclick="return confirmDelete()">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
            <button type="submit" class="btn btn-primary">Process Order</button>
        </form>
    </div>
</div>