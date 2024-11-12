<?php

$url = 'http://localhost/php-rest-tp1/api/v1.0/produit/list';

try {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    var_dump(json_decode($response));
} catch (Exception $e) {
    echo 'Une erreur client est survenue : ' . $e->getMessage();
}
