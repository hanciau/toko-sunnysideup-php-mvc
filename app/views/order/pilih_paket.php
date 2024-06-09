<form id="addressForm" method="post" action="<?= BASEURL; ?>/order/inputorder">
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
                <td id="totalWeight"><?= $data['total_weight'] ?></td>
            </tr>
            <tr>
            <td colspan="5" style="text-align: right;">Total harga:</td>
                <td><?=$total_hargapesanan ?></td>
                <input type="hidden" name="totalhargabarang" value="<?=$total_hargapesanan ?>">
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="address_id" id="address_id" value="<?= $data["address_id"] ?>">
    <input type="hidden" name="courier" id="courier" value="<?= $data["courier"] ?>">
    <div id="shippingPackages">
    <h3>Pilih Paket Pengiriman:</h3>
    <select id="packageOptions"required>
        <option value="">pilih paket</option>
        <?php foreach ($data['paket'] as $paket) : ?>
            <option value='{"service_name": <?= json_encode($paket["service_name"]) ?>, "service_description": <?= json_encode($paket["service_description"]) ?>, "service_cost": <?= json_encode($paket["service_cost"])  ?>}'>
                <?= htmlspecialchars($paket['service_name']) ?>, <?= htmlspecialchars($paket['service_description']) ?>, <?= htmlspecialchars($paket['service_cost']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<input type="hidden" name="service_name" id="service_name">
<input type="hidden" name="service_description" id="service_description">
<input type="hidden" name="service_cost" id="service_cost">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const packageOptionsSelect = document.getElementById('packageOptions');
        const nameInput = document.getElementById('service_name');
        const descriptionInput = document.getElementById('service_description');
        const costInput = document.getElementById('service_cost');

        packageOptionsSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedValue = JSON.parse(this.value);
                nameInput.value = selectedValue.service_name;
                descriptionInput.value = selectedValue.service_description;
                costInput.value = selectedValue.service_cost;
            } else {
                nameInput.value = '';
                descriptionInput.value = '';
                costInput.value = '';
            }
        });

        // Trigger change event on page load to set the initial values
        packageOptionsSelect.dispatchEvent(new Event('change'));
    });
</script>
    <button type="submit">Submit</button>
</form>