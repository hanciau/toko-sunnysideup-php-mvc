<?php

class getProvinces {

    private $apirajaongkir;

    public function __construct() {
        $this->apirajaongkir = API_KEY_RAJA_ONGKIR; 
    }
    public function getProvinces() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: $this->apirajaongkir"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return array('error' => 'Error fetching provinces', 'details' => $err);
        } else {
            $data = json_decode($response, true);
            $provinces = array();

            if (isset($data['rajaongkir']['results'])) {
                foreach ($data['rajaongkir']['results'] as $province) {
                    $provinces[] = array(
                        'province_id' => $province['province_id'],
                        'province' => $province['province']
                    );
                }
            }

            return $provinces;
        }
    }

    // Tambahkan metode lainnya sesuai kebutuhan API Anda
}

?>