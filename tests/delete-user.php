<?php

require('vendor/autoload.php');

use SyntoraPHP\App\Models\Http;

$http = new Http();

$http->Url('http://xflex.test/api/v1/delete-user')
    ->Method('POST')
    ->Headers(['Content-Type: application/json'])
    ->Body(json_encode([
        "id" => 1,
        "token" => "6e0987951aa4fa2a591bade51e063272bc558c01e3025bc0bae64f6ae19eab84c43220b36b52fd4a88d1e2e54203e98b3815a47b27e5ab9202eb2d482b1b6a8b"
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