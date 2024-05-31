<?php

require_once 'db.php'; // connection à la base de données

if (isset($_POST['annotations']) && !empty($_POST['annotations'])) { // annotations
    try {
        foreach ($_POST['annotations'] as $annotation) {
            $sentenceId = $annotation['id']; // id de la phrase
            $emotion = $annotation['emotion']; // trouve la case correspondante pour maj

            // on augemente de 1 l'émotion correspondante dans la bdd
            $stmt = $pdo->prepare("UPDATE userAnalysis SET $emotion = $emotion + 1 WHERE id = :id");
            $stmt->bindParam(':id', $sentenceId);
            $stmt->execute();
        }

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Pas d'annotation."]);
}
?>
