<?php

$url = 'http://localhost/php-rest-tp1/api/v1.0/produit/delete';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $data = json_encode(['id' => (int)$_GET['id']]);
} else {
    $data = json_encode(['id' => 7]);
}

try {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    var_dump(json_decode($response));
} catch (Exception $e) {
    echo 'Une erreur client est survenue : ' . $e->getMessage();
}
