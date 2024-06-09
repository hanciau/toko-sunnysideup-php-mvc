<div class="insert-container">
    
    <div class="container">
        <h1 class="text-center mb-7">Masukkan Produk</h1>
        <form action="<?=BASEURL?>/admin/proses_insert_product" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nama Barang:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi:</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Harga:</label>
                <input type="number" name="price" id="price" class="form-control" step="1000" required>
            </div>
            <div class="form-group">
                <label for="berat">Berat:</label>
                <input type="number" name="berat" id="berat" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="stock">Stok:</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategori:</label><br>
                <?php
                // Loop through categories from the database
                foreach ($data['category'] as $category) {
                    echo '<label><input type="checkbox" name="category_id[]" value="' . $category['category_id'] . '"> ' . $category['category_name'] . '</label><br>';
                }
                ?>
            </div>
            <div>
                <label for="image">Gambar:</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <br>
        </form>
    </div>
    <div class="container">
        <div class="preview">
            <img id="image-preview" src="#" alt="Preview Gambar" style="display: none;">
        </div>
    </div>
</div>
