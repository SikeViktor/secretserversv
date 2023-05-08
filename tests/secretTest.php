<?php
require_once "classes/database.php";
require_once "classes/secret.php";

use PHPUnit\Framework\TestCase;

class SecretTest extends TestCase
{
    private $secret;

    protected function setUp(): void
    {
        $this->secret = new Secret();
    }

    public function testCreateSecret()
    {
        $secretText = "test secret";
        $remainingViews = 5;
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 day'));

        $result = $this->secret->createSecret($secretText, $remainingViews, $expiresAt);

        $this->assertTrue($result);
    }

    public function testGetSecret()
    {
        $hash = "6457f646b0fb3";

        $result = $this->secret->getSecret($hash);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('hash', $result);
        $this->assertArrayHasKey('secretText', $result);
        $this->assertArrayHasKey('createdAt', $result);
        $this->assertArrayHasKey('expiresAt', $result);
        $this->assertArrayHasKey('remainingViews', $result);
    }

    public function testUpdateSecret()
    {
        $hash = "test-hash";

        $result = $this->secret->updateSecret($hash);

        $this->assertTrue($result);
    }

    public function testDeleteExpiredSecrets()
    {
        $result = $this->secret->deleteExpiredSecrets();

        $this->assertTrue($result);
    }
}
