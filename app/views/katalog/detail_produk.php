<div class="container p-0 mb-4 mt-4 rounded-5 ">
    <div class="container">
        <div class="product-container"style="margin-top: 150px;">
            <div class="product-card">
                <img src="data:image/jpeg;base64,<?= base64_encode($data['produk']['image_url']) ?>" class="product-image" />
            </div>
            <div class="product-detail-pemesanan">
                <h2><?= $data['produk']['name'] ?></h2><br>
                Stok Tersedia : <?= $data['produk']['stock'] ?><br><br>
                <div class="harga">Rp.<?= number_format($data['produk']['price'], 2) ?></div>
                <div class="category">Kategori : <?= $data['produk']['category_names'] ?></div>
                <form action="<?= BASEURL ?>/keranjang/tambahkekeranjang" method="post">
                    <label for="quantity">Jumlah:</label>
                    <div class="input-group">
                        <input type="number" id="quantity" name="quantity" value="1" required>
                    </div>
                    <input type="hidden" name="product_id" value="<?= $data['produk']['product_id'] ?>">
                    <input type="hidden" name="price" value="<?= $data['produk']['price'] ?>">
                    <button type="submit" class="btn-beli">Masukkan ke Keranjang</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="description">
    <p><?= $data['produk']['description'] ?></p>
</div>