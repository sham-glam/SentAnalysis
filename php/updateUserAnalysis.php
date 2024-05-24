<?php

if (isset($_POST['annotations']) && !empty($_POST['annotations'])) { // annotations
    $servername = "localhost";
    $username = "admin";
    $password = "Palabramagica1!";
    $dbname = "SentAnalysis";

    try {
        $pdo = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


        foreach ($_POST['annotations'] as $annotation) {
            $sentenceId = $annotation['id']; // id de la phrase
            $emotion = $annotation['emotion']; // trouver la case correspondante pour maj

            // Update the corresponding emotion count in the database
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
