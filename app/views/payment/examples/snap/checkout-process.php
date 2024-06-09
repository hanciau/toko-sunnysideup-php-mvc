<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for snap popup:
// https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview

namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
Config::$serverKey = API_SERVER;
Config::$clientKey = API_KEY_MIDTRANS;

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

// Uncomment for production environment
// Config::$isProduction = true;

// Enable sanitization
Config::$isSanitized = true;

// Enable 3D-Secure
Config::$is3ds = true;

// Uncomment for append and override notification URL
// Config::$appendNotifUrl = "https://example.com";
// Config::$overrideNotifUrl = "https://example.com";

// Required
// Mengambil data order, customer, dan item dari $data
$orderData = $data['order_data'];
$customerData = $data['customer_data'];
$item_details = $data['item_details'];

// Menyiapkan detail transaksi
$transaction_details = array(
    'order_id' => $orderData['order_id'],
    'gross_amount' => floatval($orderData['total_biaya']), // Ubah total biaya menjadi float
);

// Memformat ulang detail item
$item_details_formatted = array();
foreach ($item_details as $item) {
    $item_details_formatted[] = array(
        'id' => $item['product_id'],
        'price' => floatval($item['price']), // Ubah harga menjadi float
        'quantity' => $item['quantity'],
        'name' => 'Product Name', // Ganti 'Product Name' dengan nama produk yang sesuai
    );
}

// Menambahkan ongkos kirim sebagai item terpisah
$item_details_formatted[] = array(
    'id' => 'shipping_cost', // Menggunakan string untuk menghindari konflik dengan product_id
    'price' => floatval($orderData['ongkos_kirim']), // Ubah ongkos kirim menjadi float
    'quantity' => 1,
    'name' => 'Ongkos Kirim', // Ganti 'Ongkos Kirim' dengan nama yang sesuai
);

// Detail customer
$customer_details = array(
    'first_name' => $customerData['nama_depan'] ?: 'Customer', // Ganti 'Customer' dengan nama default jika tidak ada nama
    'last_name' => "",
    'email' => $customerData['email'],
    'phone' => "", // Jika ada nomor telepon, tambahkan di sini
    'billing_address' => "", // Jika ada alamat penagihan, tambahkan di sini
    'shipping_address' => "", // Jika ada alamat pengiriman, tambahkan di sini
);

// Menyiapkan detail transaksi untuk Midtrans
$transaction = array(
    'enabled_payments' => $enable_payments, // Pastikan $enable_payments sudah didefinisikan
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details_formatted,
);

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
}
catch (\Exception $e) {
}


function printExampleWarningMessage() {
    if (strpos(Config::$serverKey, 'your ') != false ) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
        }

        #pay-button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #pay-button:hover {
            background-color: #45a049;
        }

        #result-json {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Checkout<br>
        setelah ada memilih harap segera menyelesaikan pembayaran</h2>
        <h2 style="color:red;">mohon jangan tinggalkan halaman sebelum menyelesaikan pembayaran</h2>

        <button id="pay-button">Pay Now!</button>
    </div>
    <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey;?>"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken acquired from previous step
            snap.pay('<?php echo $snap_token?>', {
                // Optional
                onSuccess: function(result){
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML = '<pre>' + JSON.stringify(result, null, 2) + '</pre>';
                },
                // Optional
                onPending: function(result){
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML = '<pre>' + JSON.stringify(result, null, 2) + '</pre>';
                },
                // Optional
                onError: function(result){
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML = '<pre>' + JSON.stringify(result, null, 2) + '</pre>';
                }
            });
        };
    </script>
</body>
</html>