<?php


 const CSERVER ="localhost";
 const CNAME = "sbelearning";
 const CUSER = "root";	
 const CPASSWORD = "";

 
function Conection($server = null, $dbname = null, $user = null, $password = null){
    $SERVER = ($server) ? $server : CSERVER;
    $DBNAME = ($dbname) ? $dbname : CNAME;
    $USER = ($user) ? $user : CUSER;
    $PASSWORD = ($password) ? $password : CPASSWORD;

    $pdo = null;

    try {
        $pdo = new PDO("mysql:host={$SERVER};dbname={$DBNAME}", $USER, $PASSWORD);
		// $pdo->exec("set names utf8");
		// $pdo->exec("SET CHARACTER SET utf8");
    } catch (PDOException $e) {
        die($e->getMessage());
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}

const CONS_IPArchivos = "https://d2mv2wiw5k8g3l.cloudfront.net";
// const AWS_ACCES_KEY = "AKIAJRASIRETIMB4UQ7A";
// const AWS_SECRET_KEY = "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";
const AWS_ACCES_KEY_B = "AKIAVLUWWQNTDBDKZJE2";
const AWS_SECRET_KEY_B = "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";
const AWS_PATH = "owlgroup";

