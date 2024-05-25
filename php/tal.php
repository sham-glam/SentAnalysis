<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique - SentAnalysis Projet Final Programmation Web</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<body class="graphique-page">

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-custom">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="home" class="nav-link text-white" href="../index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a id="analyse" class="nav-link text-white" href="../UserAnalysis.html">Analyse Utilisateur</a>
                    </li>
                    <li class="nav-item"> 
                        <a id="input" class="nav-link text-white" href="../InputSentence.php">Input Texte</a>
                    </li>
                    <li class="nav-item active">
                        <a id="graphique" class="nav-link text-dark" href="../graphique.html">Graphique <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a id="propos" class="nav-link text-white" href="../contact.html">A propos du Projet</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="tal">
                <h3>Traitement TAL</h3>
                <p>Calcul des nombres de termes par catégorie</p>

                <!--what next-->
             <div id="calculateTal" style="height: 400px; width: 100%;"></div>
        </div>


<php?
calculateTal();



?>



        <div class="links">
    <a href="graphStats.php" class="btn btn-primary">Graphique 1</a>
    <a href="graph2.php" class="btn btn-primary">Graphique 2</a>
    <a href="../graphique.html" class="btn btn-primary">Voir toutes les options statistique</a>
    <a href="../index.html" class="btn btn-secondary">Retour à la page d'accueil</a>
    </div>
