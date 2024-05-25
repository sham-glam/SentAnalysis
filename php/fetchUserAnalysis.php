<?php
// Include the database connection file
require_once 'db.php';

try {
    // Execute the query to select all rows from the userAnalysis table
    $requete = $pdo->query("SELECT * FROM userAnalysis");
    $resultat = $requete->fetchAll(PDO::FETCH_OBJ); // récupère toutes les phrases
    $jsonData = json_encode($resultat);
    echo $jsonData; // renvoie les données au format JSON
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données " . $e->getMessage();
}
?>
