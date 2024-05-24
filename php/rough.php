<?php
    require_once 'php/db.php'; // connexion à la base de données

    function getJournalId($journal) {
        // Define a mapping of journal names to ids
        $journalIds = [
            "Le Monde" => 1,
            "Libération" => 2,
            "Le Figaro" => 3,
            "Le Monde Diplomatique" => 4,
            "divers" => 5,
        ];
    
        // Return the id based on the journal name
        return isset($journalIds[$journal]) ? $journalIds[$journal] : 5; // Default to 'divers' if not found
    }

    if(isset($_POST['submit'])){
        $phrase = trim($_POST['phrase']);
        $category = trim($_POST['category']);
        $journal = isset($_POST['journal']) ? trim($_POST['journal']) : ''; 
        $id_journal = getJournalId($journal); 
    
        if (empty($journal)) {
            $journal = "divers"; // Set default journal if empty
        }    
        $id_journal = getJournalId($journal); // Get the journal ID
    
        try {
            // Define the initial values for each category
            $heureux = $category === 'heureux' ? 1 : 0;
            $triste = $category === 'triste' ? 1 : 0;
            $colere = $category === 'colere' ? 1 : 0;
            $neutre = $category === 'neutre' ? 1 : 0;
    
            // Prepare the SQL statement with placeholders
            $stmt = $pdo->prepare("INSERT INTO userAnalysis (phrase, id_journal, journal, heureux, triste, colere, neutre) 
                                   VALUES (:phrase, :id_journal, :journal, :heureux, :triste, :colere, :neutre)");
    
            // Bind the parameters to the SQL query
            $stmt->bindParam(':phrase', $phrase);
            $stmt->bindParam(':id_journal', $id_journal);
            $stmt->bindParam(':journal', $journal);
            $stmt->bindParam(':heureux', $heureux);
            $stmt->bindParam(':triste', $triste);
            $stmt->bindParam(':colere', $colere);
            $stmt->bindParam(':neutre', $neutre);
    
            // Execute the statement
            $stmt->execute();
    
            echo "Vos données ont été enregistrées!";
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    } else {
        echo "Veuillez entrer un texte et une catégorie.";
    }

    $result=$pdo->query("SELECT * FROM userAnalysis");

    if($result){
        header("Location: UserAnalysis.html");
    }
    else{
        echo "Erreur: " . $e->getMessage();
    }


?>

