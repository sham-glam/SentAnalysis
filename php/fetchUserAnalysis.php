<?php
// Database connection
$servername = "localhost";
$username = "admin";
$password = "Palabramagica1!";
$dbname = "SentAnalysis";

try {
    // création d'une instace PDO
    $sql = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    $requete = $sql->prepare("SELECT * FROM userAnalysis");
    $requete->execute();
    $resultat = $requete->fetchAll(PDO::FETCH_OBJ); // récupère toutes les phrases
    $jsonData = json_encode($resultat);
    echo $jsonData; // renvoie les données au format JSON

} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données " . $e->getMessage();
}



?>
