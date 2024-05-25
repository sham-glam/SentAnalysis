<?php

// Database connection
$servername = "localhost";
$username = "admin";
$password = "Palabramagica1!";
$dbname = "SentAnalysis";

try {
    $pdo = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // echo "Connexion à la base de données réussie"; // pour deboggage
} 
catch(PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
    die();
}

?>
