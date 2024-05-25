<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emotion Analysis</title>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body>
    <div id="graph1">
        <h3>Graphique 1</h3>
        <p>Graphique de la distribution des sentiments</p>
        <canvas id="chartContainer" width="400" height="400"></canvas>
    </div>

    <?php
    require_once 'db.php';

    $query = "SELECT journal, MAX(heureux) as max_heureux, MAX(triste) as max_triste, MAX(colere) as max_colere, MAX(neutre) as max_neutre, COUNT(*) as total_rows FROM userAnalysis GROUP BY journal";
    $result = $pdo->query($query);

    $dataPoints = array();

    while ($row = $result->fetch(PDO::FETCH_OBJ)) {
        $max_total = $row->max_heureux + $row->max_triste + $row->max_colere + $row->max_neutre;
        $data = array(
            "label" => $row->journal,
            "y" => array(
                array("label" => "Heureux", "y" => ($row->max_heureux / $max_total) * $row->total_rows),
                array("label" => "Triste", "y" => ($row->max_triste / $max_total) * $row->total_rows),
                array("label" => "Colère", "y" => ($row->max_colere / $max_total) * $row->total_rows),
                array("label" => "Neutre", "y" => ($row->max_neutre / $max_total) * $row->total_rows)
            )
        );
        array_push($dataPoints, $data);
    }

    $pdo = null;
    ?>

    <script>

    window.onload = function() {

    var chart = new CanvasJS.Chart("chartContainer", {
        theme: "light2",
        animationEnabled: true,
        title: {
            text: "Emotion Analysis by Journal"
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
            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });

    chart.render();

    }
    </script>
</body>
</html>
