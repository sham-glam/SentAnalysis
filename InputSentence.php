<?php
session_start();
require_once 'php/db.php'; // Database connection

// correspondance entre les journaux et les identifiants
function getJournalId($journal) {
    $journalIds = [
        "Le Monde" => 1,
        "Libération" => 2,
        "Le Figaro" => 3,
        "Le Monde Diplomatique" => 4,
        "divers" => 5,
    ];

    return isset($journalIds[$journal]) ? $journalIds[$journal] : 5; // Default value
}

// fonction qui vérifie si une phrase existe déjà dans la base de données   
function phraseExists($phrase) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM userAnalysis WHERE phrase = ?");
    $stmt->execute([$phrase]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    return $result;
}

// fonction qui récupère toutes les phrases ajoutées par l'utilisateur de la session actuel
function getAllAddedPhrases() {
    global $pdo;
    $phrases = [];
    if(isset($_SESSION['added_ids']) && !empty($_SESSION['added_ids'])) {
        foreach($_SESSION['added_ids'] as $id) {
            $stmt = $pdo->prepare("SELECT * FROM userAnalysis WHERE id = ?");
            $stmt->execute([$id]);
            while($phrase = $stmt->fetch(PDO::FETCH_OBJ)) {
                $phrases[] = $phrase; // Ajoute la phrase à la liste
            }
        }
    }
    return $phrases;
}

// initialisation de la variable de session pour stocker les identifiants des phrases ajoutées
if (!isset($_SESSION['added_ids'])) {
    $_SESSION['added_ids'] = [];
}

// Traitement du formulaire
if (isset($_POST['submit'])) {
    $phrase = trim($_POST['phrase']);
    $category = trim($_POST['category']);
    $journal = isset($_POST['journal']) ? trim($_POST['journal']) : 'divers';
    $id_journal = getJournalId($journal);
    try {
        if (strlen($phrase) > 3 && !empty($phrase)) {
            if (phraseExists($phrase)) {
                echo "Cette phrase existe déjà dans la base de données.";
            } else {
                // vérifie si la catégorie 
                $heureux = $category === 'heureux' ? 1 : 0;
                $triste = $category === 'triste' ? 1 : 0;
                $colere = $category === 'colere' ? 1 : 0;
                $neutre = $category === 'neutre' ? 1 : 0;

                // prépare la requête SQL avec des placeholders
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

                
                $stmt->execute();

                // Ajoute l'identifiant de la phrase ajoutée à la variable de session
                $_SESSION['added_ids'][] = $pdo->lastInsertId();

                echo "<p>Vos données ont été enregistrées!</p>";
            
            }
        } else {
            echo "La phrase doit contenir au moins 4 caractères et ne peut pas être vide.";
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

// Suppression des phrases ajoutées si l'utilisateur clique sur le bouton "Confirmer la suppression"
if(isset($_POST['confirm_delete_phrases'])) {
    if(isset($_POST['delete'])) {
        foreach($_POST['delete'] as $phraseId) {
            $stmt = $pdo->prepare("DELETE FROM userAnalysis WHERE id = ?");
            $stmt->execute([$phraseId]);
        }
        echo "<p>Phrases supprimées avec succès.</p>";
    } else {
        echo "<p>Aucune phrase sélectionnée pour la suppression.</p>";
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="js/InputSentence.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="user-input-page">
<header>
   <h4>Insertion des pharses par l'utilisateur.</h4>
</header>
<main>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-custom">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="home" class="nav-link text-white" href="index.html">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a id="analyse" class="nav-link text-white" href="UserAnalysis.html">Analyse Utilisateur</a>
                    </li>
                    <li class="nav-item active">
                        <a id="input" class="nav-link text-dark" href="InputSentence.php">Input Texte <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a id="graphique" class="nav-link text-white" href="graphique.html">Graphique</a>
                    </li>
                    <li class="nav-item">
                        <a id="propos" class="nav-link text-white" href="contact.html">A propos du Projet</a>
                    </li>
                </ul>
                <div class="ml-auto">
                <a href="php/formulaire_contact.php" class="btn btn-outline-light">Laisser nous un commentaire</a>
                </div>
            </div>
        </nav>

        <div class="content">
            <?php if(isset($_POST['view_added'])): ?>
                <div id="resume">
                    <h4>Résumé des phrases ajoutées :</h4>
                    <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                        <th>Phrase</th>
                        <th>Source</th>
                        <th>Heureux</th>
                        <th>Triste</th>
                        <th>Colère</th>
                        <th>Neutre/Inconnu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $addedPhrases = getAllAddedPhrases();
                        foreach($addedPhrases as $phrase): ?>
                            <tr>
                                <td><?php echo $phrase->phrase; ?></td>
                                <td><?php echo $phrase->journal; ?></td>
                                <td><?php echo $phrase->heureux; ?></td>
                                <td><?php echo $phrase->triste; ?></td>
                                <td><?php echo $phrase->colere; ?></td>
                                <td><?php echo $phrase->neutre; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <form method="post">
                    
                    <?php if (!empty(getAllAddedPhrases())): ?>

                    <button id="delete_phrases" type="submit" class="btn btn-danger" name="delete_phrases">Supprimer</button>
                    <?php else: ?>
                        <p>Pas de phrases ajoutées.<br>
                            Veuillez ajouter des phrases.</p><br>
                    <?php endif; ?>
                    <a href="InputSentence.php" class="btn btn-primary">Ajouter une phrase</a>
                    <a href="index.html" class="btn btn-primary">Accueil</a>

               </form>
                

            <?php elseif(isset($_POST['submit'])): ?>
                <?php if (isset($errorMsg)) {
                    echo "<p>$errorMsg</p>";
                } ?>
                <form method="post">
                    <a href="InputSentence.php" class="btn btn-primary">Insérer une phrase</a>
                    <a href="index.html" class="btn btn-primary">Accueil</a>

                <?php if (!empty(getAllAddedPhrases())): ?>
                    <button id="delete_phrases" type="submit" class="btn btn-danger" name="delete_phrases">Supprimer</button>
                <?php endif; ?>

                </form>
                </div>
            <?php else: ?>
                <div id="inputSentence display:none">
                <!-- formulaire-->
                <form method="post">
                 <!-- id="inputSentence"` -->
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
                                        <option value="divers">Divers</option> <!-- Default value -->
                                        <option value="Le Monde">Le Monde</option>
                                        <option value="Libération">Libération</option>
                                        <option value="Le Figaro">Le Figaro</option>
                                        <option value="Le Monde Diplomatique">Le Monde Diplomatique</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="category" class="form-control" style="height: 100%;" required>
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
               
                <!-- Show the "Visionner les phrases ajoutées" button below the form -->
                <form method="post">
                    <button id="view_added" type="submit" class="btn btn-success" name="view_added">Visionner les phrases ajoutées</button>
                </form>
                </div>
                
            <?php endif; ?>

            <?php if(isset($_POST['delete_phrases'])): ?>
                <div id="delete_phrases">
                <h3>Résumé des phrases ajoutées :</h3>
                <form method="post">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Selectionner</th> <!-- Checkbox column -->
                                <th>Phrase</th>
                                <th>Source</th>
                                <th>Heureux</th>
                                <th>Triste</th>
                                <th>Colère</th>
                                <th>Neutre/Inconnu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $addedPhrases = getAllAddedPhrases();
                            foreach($addedPhrases as $phrase): ?>
                                <tr>
                                    <td><input type="checkbox" name="delete[]" value="<?php echo $phrase->id; ?>"></td> <!-- Checkbox -->
                                    <td><?php echo $phrase->phrase; ?></td>
                                    <td><?php echo $phrase->journal; ?></td>
                                    <td><?php echo $phrase->heureux; ?></td>
                                    <td><?php echo $phrase->triste; ?></td>
                                    <td><?php echo $phrase->colere; ?></td>
                                    <td><?php echo $phrase->neutre; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                            </table>
                    <button type="submit" class="btn btn-danger" name="confirm_delete_phrases">Confirmer la suppression</button>
                    <a href="InputSentence.php" class="btn btn-primary">Ajouter une phrase</a>
                    <a href="index.html" class="btn btn-primary">Page d'Accueil</a>
                </form>
                </div>
            <?php endif; ?>

        <!-- Footer -->
        
    </div>
</main>
<footer class="footer">
            <p>Shami THIRION SEN &copy; 2024 Projet Web Programmation</p>
</footer>
</body>
</html>
