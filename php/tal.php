<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique - SentAnalysis Projet Final Programmation Web</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<body class="graphique-page">
<br><br><br>

<?php
echo "debug 1";
?>
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
                <br/><br/><br/><br/><br/>
                <h3>Traitement TAL</h3>
                <p>Calcul des termes les plus fréquents par catégorie</p>
                <!-- <div id="calculateTal" style="height: 400px; width: 100%;"> -->
                

<?php

require_once 'db.php';
// console.log("Connecting to the database");  



$query = "SELECT phrase, heureux, triste, colere, neutre FROM userAnalysis";
$stmt = $pdo->query($query);

$categories = array(
    'heureux' => array(),
    'triste' => array(),
    'colere' => array(),
    'neutre' => array()
);

while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    $max_score = max($row->heureux, $row->triste, $row->colere, $row->neutre);
    $max_category = '';
    switch ($max_score) {
        case $row->heureux:
            $max_category = 'heureux';
            break;
        case $row->triste:
            $max_category = 'triste';
            break;
        case $row->colere:
            $max_category = 'colere';
            break;
        case $row->neutre:
            $max_category = 'neutre';
            break;
        default:
            $max_category = 'neutre';
    }

    $categories[$max_category][] = $row->phrase;
} // end while


$stop_words = array('et', 'à', 'le', 'la', 'les', 'des', 'du', 'un', 'une', 'de', 'en', 'pour', 'avec', 'par', 'sur', 'au', 'aux', 'dans', 'ne', 'pas', 'que', 'qui', 'ce', 'se', 
'sa', 'son', 'ses', 'lui', 'leur', 'ils', 'elles', 'nous', 'vous', 'elle', 'il', 'tu', 'moi', 'toi', 'eux', 'elles', 'nous', 'vous', 'leur', 'leurs', 'mon', 'ton', 'son', 'notre'
,'qu\'il', 'qu\'elle', 'qu\'ils', 'qu\'elles', 'qu\'on', 'qu\'en', 'qu\'y', 'qu\'à', 'qu\'au', 'qu\'aux', 'qu\'un', 'qu\'une', 'qu\'il', 'qu\'elle', 'qu\'ils', 'qu\'elles', 'qu\'on',
'est', 'sont', 'a', 'l\'');

foreach ($categories as $emotion => $sentences) {
    $all_text = implode(' ', $sentences);
    $all_text = remove_named_entities($all_text);
    $word_count = array();
    $all_text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $all_text);
    $words = explode(' ', strtolower($all_text));

    foreach ($words as $word) {
        if (!in_array($word, $stop_words) && strlen($word) > 2) {
            if (!isset($word_count[$word])) {
                $word_count[$word] = 0;
            }
            $word_count[$word]++;
        }
    }
    arsort($word_count);
    $top_n = 10;
    echo "<h4>" . ucfirst($emotion) . "</h4>"; // nom de l'émotion    
    echo "<table border='2' table-dark table-striped style='margin: 0 auto;'>";
    
    echo "<tr><th>Word</th><th>Count</th></tr>";
    // echo "<tr>" . ucfirst($emotion) . "</tr>";
    $i = 0;
    foreach ($word_count as $word => $count) {
        if ($i >= $top_n) {
            break;
        }
        echo "<tr><td>$word</td><td>$count</td></tr>";
        $i++;
    }
    echo "</table><br><br><br>";
}

function remove_named_entities($text) {
    $words = explode(' ', $text);
    $filtered_words = array();
    foreach ($words as $word) {
        if (!(ctype_upper($word[0]) && strlen($word) > 1)) {
            array_push($filtered_words, $word);
        }
    }
    return implode(' ', $filtered_words);
}

?>


</div>
</div>

    <div class="links">
    <br><br><br>
    <a href="graphStats.php" class="btn btn-primary">Graphique 1</a>
    <a href="graph2.php" class="btn btn-primary">Graphique 2</a>
    <a href="../graphique.html" class="btn btn-primary">Voir toutes les options statistique</a>
    <a href="../index.html" class="btn btn-secondary">Retour à la page d'accueil</a>
    <br><br><br>
    </div>

</div>

</body>
</html>
