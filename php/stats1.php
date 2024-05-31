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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="graphique-page">

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
                    <li class="nav-item active">
                        <a id="graphique" class="nav-link text-dark" href="../graphique.html">Graphique <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a id="propos" class="nav-link text-white" href="../contact.html">A propos du Projet</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="graph1">
            <h3>Graphique 1</h3>
            <div id="chartContainer" style="height: 400px; width: 100%;"></div>
            <br/><br/><br/>

        </div>
    </div>

    <?php
        require_once 'db.php';

        // echo "<!-- Connecting to the database -->\n";
        $query = "SELECT SUM(heureux) as sum_heureux, SUM(triste) as sum_triste, SUM(colere) as sum_colere, SUM(neutre) as sum_neutre FROM userAnalysis";
        $result = $pdo->query($query);

        if (!$result) {
            echo "<!-- Query failed: " . $pdo->errorInfo()[2] . " -->\n";
        }

        $row = $result->fetch(PDO::FETCH_OBJ);
        $dataPoints = array(
            array("label" => "Heureux", "y" => $row->sum_heureux),
            array("label" => "Triste", "y" => $row->sum_triste),
            array("label" => "Colère", "y" => $row->sum_colere),
            array("label" => "Neutre", "y" => $row->sum_neutre)
        );

        $pdo = null;
    ?>

    <script>
    window.onload = function() {
        console.log("Window loaded, initializing chart...");
        var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
        console.log("Data points: ", dataPoints);

        var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            animationEnabled: true,
            title: {
                text: "Distribution des émotions annotées à travers les phrases"
            },
            data: [{
                type: "pie",
                indexLabel: "{label}: {y}",
                indexLabelPlacement: "inside",
                indexLabelFontColor: "#36454F",
                indexLabelFontSize: 18,
                indexLabelFontWeight: "bolder",
                showInLegend: true,
                legendText: "{label}",
                dataPoints: dataPoints
            }]
        });

        chart.render();
        console.log("Chart rendered successfully.");
    }
    </script>
    <div class="links">
    <a href="stats2.php" class="btn btn-primary">Graphique 2</a>
    <a href="stats3.php" class="btn btn-primary">Graphique 3</a>
    <a href="../graphique.html" class="btn btn-primary">Voir toutes les options statistique</a>
    <a href="../index.html" class="btn btn-secondary">Retour à la page d'accueil</a>
    <br/><br/><br/>
    </div>
</body>
</html>
