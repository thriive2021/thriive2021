<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://etsrds.kapps.in/webapi/enterprise/v1/obd_quickcall.py');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$post = array(
    'customer_number' => '+917999886050',
    'k_number' => '+918035387035',
    'ivr_id' => '1000057656',
    'custom_params' => 'Anmol',
    'is_transactional' => 'yes'
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$headers = array();
$headers[] = 'Authorization: 28637081-d7a7-4bf5-96aa-c79fbf2aba42';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

echo $result;
curl_close($ch);