<div class="container p-0 mb-4 mt-4 rounded-5">
    <div class="container">
        <div class="product-container" style="margin-top: 150px;">
            <div class="product-card">
                <img src="data:image/jpeg;base64,<?= base64_encode($data['produk']['image_url']) ?>" class="product-image" />
                <button type="button" class="btn-update" data-bs-toggle="modal" data-bs-target="#imageModal">Ganti Gambar</button>
            </div>
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel">Ganti gambar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="imageForm" action="<?= BASEURL ?>/admin/updateimagebaru" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="Image">Pilih Gambar</label>
                                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                                    <input type="hidden" name="product_id" value="<?= $data['produk']['product_id'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <img id="image-preview" src="#" alt="Preview Gambar" style="display: none; max-width: 100%; height: auto;">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-detail-pemesanan">
                <form action="<?= BASEURL ?>/admin/prosesedit" method="post">
                    <input type="hidden" name="product_id" value="<?= $data['produk']['product_id'] ?>" required>
                    <h2><input type="text" name="name" value="<?= $data['produk']['name'] ?>" required></h2><br>
                    <label for="stock">Stok Tersedia:</label>
                    <input type="number" id="stock" name="stock" value="<?= $data['produk']['stock'] ?>" required><br><br>
                    <label for="price">Harga:</label>
                    <input type="text" id="price" name="price" value="<?=$data['produk']['price'] ?>" required><br><br>
                    <label for="category">Kategori:</label>
                    <input type="text" id="category" name="category" value="<?= $data['produk']['category_names'] ?>"><br><br>
            </div>
        </div>
    </div>
</div>
<div class="description">
    <label for="description">Deskripsi:</label>
    <textarea id="description" name="description" required><?= $data['produk']['description'] ?></textarea><br><br>
    <button type="submit" class="btn-update">Simpan Perubahan</button>
    </form>
</div>