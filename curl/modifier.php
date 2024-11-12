<?php

$url = 'http://localhost/php-rest-tp1/api/v1.0/produit/update';

$data = json_encode([
    'id' => 5,
    'nom' => 'Produit modifié en cURL',
    'description' => 'Description produit modifié en cURL',
    'prix' => 798,
    'date_creation' => date("Y:m:d H:i:s")
]);

try {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    var_dump(json_decode($response));
} catch (Exception $e) {
    echo 'Une erreur client est survenue : ' . $e->getMessage();
}
