<form id="addressForm" method="POST" action="<?= BASEURL; ?>/order/getCoast">
    <!-- Tabel untuk menampilkan barang -->
    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama Barang</th>
                <th>Kuantitas</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Berat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_hargapesanan = 0;
            foreach ($data['cart_items'] as $item) : ?>
                <tr>
                    <td><img src="data:image/jpeg;base64,<?= base64_encode($item['image_url']) ?>" alt="<?= $item['item_name'] ?>" width="50"></td>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                    <td><?= number_format($item['harga_seluruh'], 2) ?></td>
                    <td><?= $item['total_weight'] ?> </td>
                </tr>

                <input type="hidden" name="selected_items[]" value="<?= $item['id'] ?>">
            <?php
                $total_hargapesanan += $item['harga_seluruh'];
            endforeach; ?>
            <tr>
                <td colspan="5" style="text-align: right;">Total Berat:</td>
                <td id="totalWeight"><?= $data['total_weight'] ?>
                    <input type="hidden" name="total_weight" id="total_weight" value="<?= $data['total_weight'] ?>">
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right;">Total harga:</td>
                <td><input type="text" id="totalhargabarang" value="<?= number_format($total_hargapesanan, 2) ?>" readonly></td>
            </tr>
        </tbody>
    </table>


    <label for="destination">Pilih Alamat:</label>
    <select name="selected_address" id="selected_address"required>
        <option value="">pilih alamat</option>
        <?php foreach ($data['alamat'] as $alamat) : ?>
            <option value='{"kota_id": <?= $alamat["kota_id"] ?>, "address_id": <?= $alamat["address_id"] ?>}'><?= $alamat['address'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="courier">Pilih Kurir:</label>
    <select name="courier" id="courier"required>
        <option value="">pilih kurir</option>
        <option value="jne">JNE</option>
        <option value="tiki">TIKI</option>
        <option value="pos">POS Indonesia</option>
    </select>
    <button type="submit">Submit</button>
</form>