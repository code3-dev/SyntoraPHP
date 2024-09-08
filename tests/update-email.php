<?php

require('vendor/autoload.php');

use SyntoraPHP\App\Models\Http;

$http = new Http();

$http->Url('http://xflex.test/api/v1/update-email')
    ->Method('POST')
    ->Headers(['Content-Type: application/json'])
    ->Body(json_encode([
        "id" => 1,
        "email" => "h3dev.pira@gmail.com",
        "token" => "6028b674f239905fa7df9a27717e00f4"
    ]));

try {
    $response = $http->Send();

    if ($response === false) {
        throw new \Exception("Failed to get a response from server.");
    }

    $responseArray = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Failed to decode JSON: " . json_last_error_msg());
    }

    if ($http->getStatus() === 200) {
        if (isset($responseArray['status']) && $responseArray['status']) {
            print_r($responseArray);
        } else {
            echo "Error: " . (isset($responseArray['message']) ? $responseArray['message'] : 'Unknown error');
        }
    } else {
        echo "Request failed with status code: " . $http->getStatus();
        echo "<br>Error Message: " . (isset($responseArray['message']) ? $responseArray['message'] : 'Unknown error');
    }
} catch (\Exception $e) {
    echo 'Request failed. ' . $e->getMessage();
}