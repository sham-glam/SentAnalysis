<?php
// Afficher les erreurs
session_start();
//
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php'; // Database Connection

echo "<p>Database connection successful...  </p>";

if (isset($_POST['submit'])) {
    echo "<p>Form being submitted...  </p>";

    // Fetch form data
    $sexe = $_POST['sexe'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $email = $_POST['email'];
    $commentaire = $_POST['commentaire'];

    // vérification de l'âge
    if($date_naissance != '') {
        $date_naissance = date('Y-m-d', strtotime($date_naissance));
        $date_actuelle = date('Y-m-d');
        $diff = date_diff(date_create($date_naissance), date_create($date_actuelle));
        $age = $diff->format('%y');
        if($age < 10) {
            echo "<script>alert('Vous devez avoir plus de 10 ans pour laisser un commentaire.')</script>";
            echo "<script>window.open('formulaire_contact.php', '_self')</script>";
            exit();
        } else {
            echo "<script>window.open('../contact.html', '_self')</script>";
            echo "<p>Merci pour votre participation. </p>";
        }
    }

    // Prepare and execute SQL statement
    $sql = "INSERT INTO commentaires (sexe, nom, prenom, date_naissance, email, commentaire) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt) {
        if ($stmt->execute([$sexe, $nom, $prenom, $date_naissance, $email, $commentaire])) {
            echo "<p>Commentaire enregistré avec succès...  </p>";
            echo "<p>Votre commentaire a été enregistré avec succès.</p>";
            header('Location: ../contact.html'); // Rediriger vers la page de contact
            exit();
        } else {
            echo "<script>alert('Une erreur est survenue lors de l'enregistrement du commentaire. Veuillez réessayer.')</script>";
            echo "<p>Erreur lors de l'enregistrement du commentaire. Veuillez réessayer.</p>";
        }
    } else {
        echo "<script>alert('Erreur lors de la préparation de la requête.')</script>";
        echo "<p>Erreur lors de la préparation de la requête.</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - SentAnalysis Projet Final Programmation Web</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="contact-page">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-custom">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="home" class="nav-link text-white" href="../index.html">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a id="analyse" class="nav-link text-white" href="../UserAnalysis.html">Analyse Utilisateur</a>
                    </li>
                    <li class="nav-item">
                        <a id="input" class="nav-link text-white" href="../InputSentence.php">Input Texte</a>
                    </li>
                    <li class="nav-item">
                        <a id="graphique" class="nav-link text-white" href="../graphique.html">Graphique</a>
                    </li>
                    <li class="nav-item active">
                        <a id="propos" class="nav-link text-dark" href="../contact.html">A propos du Projet <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
               
            </div>
        </nav>


       <!-- Formulaire de commentaire -->
       <div class="container mt-5">
            <h2>Formulaire de Commentaire</h2>
            <form id="formulaire_contact" method="POST" action="">
                <div class="form-group">
                    <label for="sexe">Sexe:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexe" id="male" value="male" required>
                        <label class="form-check-label" for="male">Homme</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexe" id="female" value="female" required>
                        <label class="form-check-label" for="female">Femme</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom:</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                </div>
                <div class="form-group">
                    <label for="date_naissance">Date de Naissance:</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre adresse email" required>
                </div>
                <div class="form-group">
                    <label for="commentaire">Commentaire:</label>
                    <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

       <!--formulaire de commentaire-->

        <footer class="footer">
            <p>Shami THIRION SEN &copy; 2024 Projet Web Programmation</p>
        </footer>
    </div>


</body>
</html>
