<?php
session_start();
require_once 'php/db.php'; // Database connection

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

function getAllSentences() {
    global $pdo;
    $sentences = [];
    try {
        $stmt = $pdo->query("SELECT * FROM userAnalysis");
        $sentences = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
    return $sentences;
}

function deleteSentences($ids) {
    global $pdo;
    try {
        foreach ($ids as $id) {
            $stmt = $pdo->prepare("DELETE FROM userAnalysis WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        echo "Sentences deleted successfully!";
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
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
}

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Texte - SentAnalysis Projet Final Programmation Web</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body class="user-input-page">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-custom">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="home" class="nav-link text-white" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a id="analyse" class="nav-link text-white" href="UserAnalysis.html">Analyse Utilisateur</a>
                    </li>
                    <li class="nav-item active">
                        <a id="input" class="nav-link text-dark" href="InputSentence.php">Input Texte <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a id="graphique" class="nav-link text-white" href="graphique.php">Graphique</a>
                    </li>
                    <li class="nav-item">
                        <a id="propos" class="nav-link text-white" href="contact.html">A propos du Projet</a>
                    </li>
                </ul>
            </div>
        </nav>

      
        <div class="content">
    <!-- Main content of the Input Texte page goes here -->
    <h2>Input Texte</h2>
    <p>Welcome to the Input Text page.</p>
    <?php
    if(isset($_POST['submit'])){
        // Form submitted, show buttons for continue or delete
        echo "<p>Vos données ont été enregistrées!</p>";
        echo "<div>";
        echo "<form method='post'>";
        echo "<button type='submit' class='btn btn-primary' name='continue'>Continuer</button>";
        echo "</form>";
        echo "</div>";
        echo "<div>";
        echo "<form method='post'>";
        echo "<button type='submit' class='btn btn-danger' name='delete'>Supprimer</button>";
        echo "</form>";
        echo "</div>";
    } else {
        // Form not submitted, show the form
    ?>
    
    <form  method="post"> <!--action="php/insert.php"-->
        <table class="table table-info table-bordered">
            <thead>
                <tr>
                    <th>Entrer votre texte:</th>
                    <th>Source:</th>
                    <th>Catégorie:</th>
                    <th>Finaliser</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><textarea name="phrase" class="form-control" rows="3" cols="50" required></textarea></td>
                    <td>
                        <select name="journal" class="form-control" style="height: 100%;">
                            <option value=""></option>
                            <option value="Le Monde">Le Monde</option>
                            <option value="Libération">Libération</option>
                            <option value="Le Figaro">Le Figaro</option>
                            <option value="Le Monde Diplomatique">Le Monde Diplomatique</option>
                            <option value="divers">divers</option>
                        </select>
                    </td>
                    <td>
                        <select name="category" class="form-control" style="height: 100%;" required>
                            <option value=""></option>
                            <option value="heureux">Heureux</option>
                            <option value="triste">Triste</option>
                            <option value="colere">Colère</option>
                            <option value="neutre">Neutre/Inconnu</option>
                        </select>
                    </td>
                    <td><button type="submit" name="submit" class="btn btn-primary">Soumettre</button></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php
    }
    ?>
</div>


<footer class="footer">
    <p>Shami THIRION SEN &copy; 2024 Projet Web Programmation</p>
</footer>
    </div>
</body>
</html>
