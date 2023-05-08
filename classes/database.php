<?php

class Database {

    protected function connect() {
        try {
            /*$username = "root";
            $password = "";
            $dbh = new PDO('mysql:host=localhost;dbname=secretdb', $username, $password); */

	    $username = "id20718745_root";
            $password = "_A9g0CDC4J-wENVs";
            $dbh = new PDO('mysql:host=localhost;dbname=id20718745_secretdb', $username, $password);           

            return $dbh;
        } 
        catch (PDOException $e) {
            print "Error: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}