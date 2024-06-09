<?php

class getCoast {

    private $apirajaongkir;

    public function __construct() {
        // Anda bisa memperbarui ini untuk mengambil API key dari sumber yang sesuai
        $this->apirajaongkir = API_KEY_RAJA_ONGKIR; 
    }

    public function getCoast($kotaId, $data) {
        // Mendapatkan data dari $data
        $origin = '278';
        $destination = $kotaId;
        $weight = isset($data['total_weight']) ? $data['total_weight'] : '';
        $courier = isset($data['courier']) ? $data['courier'] : '';

        // Data untuk dikirim dalam permintaan API
        $requestData = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ];

        // URL API
        $url = "https://api.rajaongkir.com/starter/cost";

        // Header untuk permintaan API
        $headers = [
            "content-type: application/x-www-form-urlencoded",
            "key: $this->apirajaongkir", // Menggunakan API key yang telah disimpan dalam kelas
        ];

        // Konfigurasi Curl
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($requestData),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        // Eksekusi Curl
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // Meng-handle respon dari permintaan API
        if ($err) {
            return array('error' => 'Error fetching provinces', 'details' => $err);
        } else {
            $result = json_decode($response, true);
            $services = array();

            if ($result['rajaongkir']['status']['code'] == 200) {
                $costs = $result['rajaongkir']['results'][0]['costs'];
                
                foreach ($costs as $cost) {
                    $serviceName = $cost['service'];
                    $serviceDescription = $cost['description'];
                    $serviceCost = $cost['cost'][0]['value'];
                    $etd = $cost['cost'][0]['etd'];
                    
                    // Menyimpan data pengiriman ke array
                    $services[] = array(
                        'service_name' => $serviceName,
                        'service_description' => $serviceDescription,
                        'service_cost' => $serviceCost,
                        'etd' => $etd
                    );
                }
            } else {
                return array('error' => "Error in API response: {$result['rajaongkir']['status']['description']}");
            }

            return $services;
        }
    }

    // Tambahkan metode lainnya sesuai kebutuhan API Anda
}

// Contoh penggunaan
// $coastCalculator = new getCoast();
// $result = $coastCalculator->getCoast($_POST);
// var_dump($result); // Untuk debug atau menampilkan hasil
