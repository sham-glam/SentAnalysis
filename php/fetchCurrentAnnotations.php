<?php
$servername = "localhost";
$username = "admin";
$password = "Palabramagica1!";
$dbname = "SentAnalysis";

if (isset($_POST['ids']) && !empty($_POST['ids'])) {
    $annotatedIds = $_POST['ids'];

    try {
        $pdo = new PDO('mysql:host=' . $servername . ';dbname=' . $dbname, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        // Construct the query with placeholders
        $query = "SELECT * FROM userAnalysis WHERE id IN (" . implode(',', array_fill(0, count($annotatedIds), '?')) . ")";
        $statement = $pdo->prepare($query);

        // Execute the statement with the ids
        $statement->execute($annotatedIds);

        $output = ""; 
        // création du tableau html
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $output .= "<tr>";
            $output .= "<td>{$row['phrase']}</td>";
            $output .= "<td>{$row['heureux']}</td>";
            $output .= "<td>{$row['triste']}</td>";
            $output .= "<td>{$row['colere']}</td>";
            $output .= "<td>{$row['neutre']}</td>";
            $output .= "</tr>";
        }

        echo $output;

    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
} else {
    echo "Impossible de récupérer les annotations.";
}
?>
