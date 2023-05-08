<?php

//Create and refresh secret, and clear expired with xml

header('Content-Type: application/xml');
$method= $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// Create a new secret
if ($method == 'POST' && isset($_POST['secret'])) {
    $secret = new Secret();
    $secretValue = $_POST['secret'];
    $remainViews = isset($_POST['expireAfterViews']) ? (int) $_POST['expireAfterViews'] : null;
    $expiresAt = isset($_POST['expireAfter']) ? date("Y-m-d H:i:s", time() + (int) $_POST['expireAfter']*60) : null;
    
    if (empty($secretValue) || $remainViews <=0) {
        http_response_code(405);
        echo '<?xml version="1.0" encoding="UTF-8"?>
        <error>Invalid input</error>';
        exit();
    }

    if ($secret->createSecret($secretValue, 10, $expiresAt)) {
        echo '<?xml version="1.0" encoding="UTF-8"?>
        <response>
            <status>success</status>
            <message>successful operation</message>
            <secret>'.$secret->getHash().'</secret>
        </response>';
        http_response_code(200);
    }
}

// Get a secret by its generated hash, and after that clear the expired secrets
if ($method == 'GET' && isset($_GET["hash"])) {
    $secret = new Secret();
    $secretData = $secret->getSecret($_GET["hash"]);

    if ($secretData) {        
        $secret->updateSecret($secretData["hash"]);
        echo '<?xml version="1.0" encoding="UTF-8"?>
        <response>
            <status>success</status>
            <message>successful operation</message>
            <secret>
                <hash>'.$secretData["hash"].'</hash>
                <secretText>'.$secretData["secretText"].'</secretText>
                <createdAt>'.$secretData["createdAt"].'</createdAt>
                <expiresAt>'.$secretData["expiresAt"].'</expiresAt>
                <remainingViews>'.$secretData["remainingViews"].'</remainingViews>
            </secret>
        </response>';
        http_response_code(200);
    } else {
        http_response_code(404);
        echo '<?xml version="1.0" encoding="UTF-8"?>
        <response>
            <status>error</status>
            <message>Secret not found</message>
        </response>';
    }
    $secret->deleteExpiredSecrets();
}
