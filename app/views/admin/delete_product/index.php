
<div class="container mt-5 dropdown margin-top: 20px;">
    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" style="margin-top: 50px;">
        Dropdown form
    </button>
    <?php echo $data['post'];?>
    <form class="dropdown-menu p-4" method="post" action="<?= BASEURL; ?>/admin/hapus_product">
    <?php foreach ($data['category'] as $row) : ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="id[]" value="<?= $row['category_id']; ?>" id="dropdownCheck<?= $row['category_id']; ?>">
            <label class="form-check-label"  for="dropdownCheck<?= $row['category_id']; ?>">
                <?= $row['category_name']; ?>
            </label>
        </div>
    <?php endforeach; ?>
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Submit</button>
    </form>
</div>

