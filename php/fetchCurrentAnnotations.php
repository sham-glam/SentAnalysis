<?php
require_once 'db.php'; // connexion à la base de données

if (isset($_POST['ids']) && !empty($_POST['ids'])) {
    $annotatedIds = $_POST['ids'];

    try {
       $query = "SELECT * FROM userAnalysis WHERE ";
        $first = true;
        foreach ($annotatedIds as $id) {
            if (!$first) {
                $query .= " OR ";
            }
            $query .= "id = ?";
            $first = false;
        }

        $statement = $pdo->prepare($query);

        // Execute the statement with the ids
        $statement->execute($annotatedIds);

        $output = ""; 
        // création du tableau html
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $output .= "<tr>";
            $output .= "<td>{$row->phrase}</td>";
            $output .= "<td>{$row->heureux}</td>";
            $output .= "<td>{$row->triste}</td>";
            $output .= "<td>{$row->colere}</td>";
            $output .= "<td>{$row->neutre}</td>";
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
