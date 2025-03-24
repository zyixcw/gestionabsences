<?php
define("API_URL", "http://localhost:5196/api/"); // Assurez-vous que l'URL est correcte pour l'API

function callAPI($method, $endpoint, $data = false, $token = null) {
    $url = API_URL . $endpoint;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    if ($token) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ]);
    }

    if ($method == "POST") {
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method == "GET") {
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    }

    $response = curl_exec($ch);
    curl_close($ch);

    // Ajout d'une gestion d'erreur si la réponse n'est pas un JSON valide
    if (!$response) {
        return ["Message" => "Erreur de connexion avec l'API"];
    }

    return json_decode($response, true);
}
?>
