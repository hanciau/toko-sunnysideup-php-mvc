<?php

class getCitiesByProvince {

    private $apirajaongkir;

    public function __construct() {
        $this->apirajaongkir = API_KEY_RAJA_ONGKIR; 
    }

    public function getCitiesByProvince($data) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . intval($data['province_id']),
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
            return array('error' => 'Error fetching cities', 'details' => $err);
        } else {
            $data = json_decode($response, true);
            $cities = array();

            if (isset($data['rajaongkir']['results'])) {
                foreach ($data['rajaongkir']['results'] as $city) {
                    $cities[] = array(
                        'city_id' => $city['city_id'],
                        'city_name' => $city['city_name']
                    );
                }
            }

            return $cities;
        }
    }
}