<?php

//Create and refresh secret, and clear expired with json

header('Content-Type: application/json');
$method= $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// Create a new secret
if ($method == 'POST' && isset($_POST['secret'])) {
    $secret = new Secret();
    $secretValue = $_POST['secret'];
    $remainViews = isset($_POST['expireAfterViews']) ? (int) $_POST['expireAfterViews'] : null;
    $expiresAt = isset($_POST['expireAfter']) ? date("Y-m-d H:i:s", time() + (int) $_POST['expireAfter']*60) : null;
    
    if (empty($secretValue) || $remainViews <=0) {        
        echo json_encode(['error' => 'Invalid input']);
        http_response_code(405);
        exit();
    }

    if ($secret->createSecret($secretValue, 10, $expiresAt)) {
        echo json_encode(['status' => 'success', 'message' => 'successful operation', 'secret' => $secret->getHash()]);
        http_response_code(200);
    }
}

// Get a secret by its generated hash, and after that clear the expired secrets
if ($method == 'GET' && isset($_GET["hash"])) {
    $secret = new Secret();
    $secretData = $secret->getSecret($_GET["hash"]);

    if ($secretData) {        
        $secret->updateSecret($secretData["hash"]);
        echo json_encode(['status' => 'success', 'message' => 'successful operation', 'secret' => $secretData]);
        http_response_code(200);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Secret not found']);
        http_response_code(404);
    }
    $secret->deleteExpiredSecrets();
}
