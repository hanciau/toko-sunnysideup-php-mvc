<div class="container-fluid" style="margin-top: 110px;">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar shadow position-fixed" style="height: 100%">
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
        <main class="col-md-9 ms-sm-auto col-lg-10 " id="main-content" >
        <div class="profil-container rounded-5"  style="min-height: 90vh;">
        <div class=' container'>
            <h2>Daftar Alamat</h2>
            <button type="button" class="btn-primary" data-bs-toggle="modal" data-bs-target="#profileModal">
                tambah alamat
            </button>
    </div>
    <center style="margin-top: 10px;">
    <table border='1' style="border-collapse: collapse;">
    <tr>
        <th style="border: 1px solid black;">Label</th>
        <th style="border: 1px solid black;">Nama Penerima</th>
        <th style="border: 1px solid black;">Nomor Telephone</th>
        <th style="border: 1px solid black;">Alamat Lengkap</th>
        <th style="border: 1px solid black;">Kota</th>
        <th style="border: 1px solid black;">Provinsi</th>
        <th style="border: 1px solid black;">Code Pos</th>
        <th style="border: 1px solid black;">Action</th> <!-- Tambah kolom untuk tombol hapus -->
    </tr>
    <?php foreach ($data['alamat'] as $alamat) : ?>
        <tr>
            <td style="border: 1px solid black;"><?= $alamat['label']; ?></td>
            <td style="border: 1px solid black;"><?= $alamat['receiver_name']; ?></td>
            <td style="border: 1px solid black;"><?= $alamat['phone_number']; ?></td>
            <td style="border: 1px solid black;"><?= $alamat['address']; ?></td>
            <td style="border: 1px solid black;"><?= $alamat['kota']; ?></td>
            <td style="border: 1px solid black;"><?= $alamat['provinsi']; ?></td>
            <td style="border: 1px solid black;"><?= $alamat['postal_code']; ?></td>
            <td style="border: 1px solid black;"><button type="button" class="btn-danger">delete</button></td>
        </tr>
    <?php endforeach; ?>
</table>
    </center>
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Tambah/Ubah Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <center>
                        <h1>Create Address</h1>
                    </center>
                    <form action="<?= BASEURL; ?>/profile/tambahalamat" method="post">
                        <div class="mb-3">
                            <label for="newAddressLabel" class="form-label">Pilih Label Alamat:</label>
                            <select id="newAddressLabel" name="newAddressLabel" class="form-select">
                                <option value="Home">Home</option>
                                <option value="Work">Work</option>
                                <!-- Add other options as needed -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="newReceiverName" class="form-label">Nama Penerima:</label>
                            <input type="text" id="newReceiverName" name="newReceiverName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPhoneNumber" class="form-label">Nomor Telepon:</label>
                            <input type="tel" id="newPhoneNumber" name="newPhoneNumber" class="form-control" required pattern="[0-9]+" title="Please enter only numbers">
                        </div>
                        <div class="mb-3">
                            <label for="newAddress" class="form-label">Alamat Lengkap:</label>
                            <input type="text" id="newAddress" name="newAddress" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="province_id" class="form-label">Pilih Provinsi:</label>
                            <select id="province_id" name="province_id" class="form-select" onchange="populateCities()">
                                <option value="">Select Province</option>
                                <?php foreach ($data['provinces'] as $province) : ?>
                                    <option id="province_id" name="province_id" value="<?= $province['province_id']; ?>"><?= $province['province']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">Pilih Kota:</label>
                            <select id="city" name="city" class="form-select">
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="newPostalCode" class="form-label">Kode Pos:</label>
                            <input type="text" id="newPostalCode" name="newPostalCode" class="form-control" required pattern="[0-9]+" title="Please enter only numbers">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                        </div>
                        <script>
                            function populateCities() {
                                var provinceSelect = document.getElementById('province_id');
                                var citySelect = document.getElementById('city');

                                // Clear existing city options
                                citySelect.innerHTML = '<option value="" selected disabled>Loading...</option>';

                                // Check if a province is selected
                                if (provinceSelect.value !== '') {
                                    var provinceId = provinceSelect.value;

                                    fetch('<?= BASEURL; ?>/profile/getCitiesByProvince',
                                    { // Ubah URL sesuai dengan alamat yang benar
                                            method: 'POST', // Menggunakan metode POST
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded' // Tentukan tipe konten
                                            },
                                            body: 'province_id=' + encodeURIComponent(provinceId) // Kirim data dalam bentuk URL-encoded
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            citySelect.innerHTML = '<option value="" selected disabled>Pilih Kota</option>';
                                            data.forEach(city => {
                                                var option = new Option(city.city_name, city.city_id);
                                                citySelect.add(option);
                                            });
                                        })
                                        .catch(error => {
                                            console.error('Error fetching cities:', error.message);
                                            citySelect.innerHTML = '<option value="" selected disabled>Error fetching cities</option>';
                                        });
                                } else {
                                    // If no province is selected, disable the city select
                                    citySelect.innerHTML = '<option value="" selected disabled>Pilih Provinsi terlebih dahulu</option>';
                                }
                            }
                        </script>
                        <hr>
                </div>
            </div>
        </div>
    </div>
</div>

</main>
</div>
</div>