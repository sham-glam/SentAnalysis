<?php

// Database connection
$servername = "localhost";
$username = "admin";
$password = "Palabramagica1!";
$dbname = "SentAnalysis";


try {
    $sql = new PDO('mysql:host='.$servername.';dbname='.$bdname, $username, $password,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")) ;
    } 
    catch(PDOException $e) {
    echo "Erreur de connexion à la base de données " . $e->getMessage() ;
    die();
    }

?>