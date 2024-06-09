<div class='container mt-5'>
    <h2>Produk Dipesan</h2>
    <ul class="row row-cols-1 row-cols-md-4">
        <?php foreach ($data['dipesan'] as $produk) : ?>
            <div class="col mb-5" style="margin-top: 50px;">
                <div class="card-product" style="margin-right: 14px; margin-left: 14px;" onclick="redirectToDetail('<?= BASEURL; ?>/admin/edit_detail/<?= $produk['product_id']; ?>')">
                    <img src="data:image/jpeg;base64,<?= base64_encode($produk['image_url']) ?>" class="product-image-card" width="100%" height="250px" />
                    <div class="product-title"><?= $produk['name'] ?></div>
                    <div class="product-price">Rp.<?= number_format($produk['price'], 2) ?></div>
                    <p class="card-text"><?= $produk['description'] ?></p>
                    <p class="card-text"><strong>Categories: </strong><?= $produk['category_names'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </ul>

    <h2>Produk Ready</h2>
    <form action="<?= BASEURL; ?>/admin/hapus" method="post">
        <ul class="row row-cols-1 row-cols-md-4">
            <?php foreach ($data['ready'] as $produk) : ?>
                <div class="col mb-5" style="margin-top: 50px;">
                    <center>
                        <input type="checkbox" name="id[]" value="<?= $produk['product_id'] ?>">
                    </center>
                    <div class="card-product" style="margin-right: 14px; margin-left: 14px;" onclick="redirectToDetail('<?= BASEURL; ?>/admin/edit_detail/<?= $produk['product_id']; ?>')">
                        <img src="data:image/jpeg;base64,<?= base64_encode($produk['image_url']) ?>" class="product-image-card" width="100%" height="250px" />
                        <div class="product-title"><?= $produk['name'] ?></div>
                        <div class="product-price">Rp.<?= number_format($produk['price'], 2) ?></div>
                        <p class="card-text"><?= $produk['description'] ?></p>
                        <p class="card-text"><strong>Categories: </strong><?= $produk['category_names'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </ul>
        <button type="submit" class="btn btn-danger position-fixed bottom-0 start-0">Hapus Produk Terpilih</button>
    </form>

</div>