<?php

class Secret extends Database
{
    private $hash;
    private $secretText;
    private $createdAt;
    private $expiresAt;
    private $remainingViews;


    //Getters
    public function getHash() {
        return $this->hash;
    }

    public function getSecretText() {
        return $this->secretText;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getExpiresAt() {
        return $this->expiresAt;
    }

    public function getRemainingViews() {
        return $this->remainingViews;
    }

    //create a secret, with unique hash
    public function createSecret($secretText, $remainingViews, $expiresAt)
    {
        try {            
            $hash=uniqid();
            $stmt = $this->connect()->prepare("INSERT INTO secrets (secretText, hash, remainingViews, expiresAt)
                                               VALUES (?, ?, ?, ?)");

            if (!$stmt->execute(array($secretText, $hash, $remainingViews, $expiresAt))) {
                $stmt = null;
                exit();
            }
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //get one secret's data by hash
    public function getSecret($hash)
    {
        try {
            $stmt = $this->connect()->prepare("
                SELECT * FROM secrets WHERE hash=? AND (remainingViews > 0 OR expiresAt > TIMESTAMPDIFF(SECOND, createdAt, NOW()))");
            $stmt->bindParam(1, $hash);
            $stmt->execute();
            $secret = $stmt->fetch(PDO::FETCH_ASSOC);
            return $secret;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //Decrease remainingViews's value by 1, if someone read it's data
    public function updateSecret($hash)
    {
        try {
            $stmt = $this->connect()->prepare("UPDATE secrets
                                               SET remainingViews = remainingViews - 1 
                                               WHERE hash = ? AND remainingViews > 0");
            $stmt->bindParam(1, $hash);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //Delete expired secrets
    public function deleteExpiredSecrets()
    {
        try {
            $stmt = $this->connect()->prepare("DELETE FROM secrets WHERE remainingViews = 0 OR expiresAt < NOW()");
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }    
}
