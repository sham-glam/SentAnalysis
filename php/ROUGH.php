<?php

require_once 'db.php';

$query = "SELECT phrase, heureux, triste, colere, neutre FROM UserAnalysis";
$stmt = $pdo->query($query);

if (!$stmt) {
    echo "<!-- Erreur: " . $pdo->errorInfo()[2] . " -->\n";
} else {
    $categories = [
        'heureux' => [],
        'triste' => [],
        'colere' => [],
        'neutre' => []
    ];

    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $max_score = max($row->heureux, $row->triste, $row->colere, $row->neutre);
        $max_category = '';
        // on prends le max_score comme la catégorie d'une phrase x
        switch ($max_score) {
            case $row->heureux:
                $max_category = 'heureux'; // si max = heureux, phrase catégorisé comme heureux etc.
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
    }

    $stop_words = ['et', 'à', 'le', 'la', 'les', 'des', 'du', 'un', 'une', 'de', 'en', 'pour', 'avec', 'par', 'sur', 'au', 'aux', 'dans', 'ne', 'pas', 'que', 'qui', 'ce', 'se', 'sa', 'son', 'ses', 'lui', 'leur', 'ils', 'elles', 'nous', 'vous', 'elle', 'il', 'tu'];

    $top_words = [];
    foreach ($categories as $emotion => $sentences) {
        $all_text = implode(' ', $sentences);
        $all_text = remove_named_entities($all_text);
        $top_words[$emotion] = get_top_words($all_text, $stop_words);
    }

    // tableau html
    foreach ($top_words as $emotion => $words) {
        echo "<h2>" . ucfirst($emotion) . "</h2>";
        echo liste_puce($words);
        echo tableau($words);
    }
}

// fonction qui enlève les mots commençant en maj - comme entités nommés
function remove_named_entities($text) {
    $words = explode(' ', $text); //split(aux espaces)
    $filtered_words = [];
    foreach ($words as $word) {
        if (!(ctype_upper($word[0]) && strlen($word) > 1)) { // verife si 1er lettre n'est pas maj et si longueur > 1
            array_push($filtered_words, $word);
        }
    }
    return implode(' ', $filtered_words); // concaténation
}

// fonction qui retourne les mots les plus fréquents 
function get_top_words($text, $stop_words, $top_n = 10) {
    $words = explode(' ', strtolower($text));
    $filtered_words = [];
    foreach ($words as $word) {
        if (!in_array($word, $stop_words) && strlen($word) > 2) {
            array_push($filtered_words, $word);
        }
    }
    $word_count = [];
    foreach ($filtered_words as $word) {
        if (!isset($word_count[$word])) {
            $word_count[$word] = 0;
        }
        $word_count[$word]++;
    }
    arsort($word_count); // trier par valeur
    $top_words = [];
    $i = 0;
    foreach ($word_count as $word => $count) {
        if ($i >= $top_n) { // limiter à top_n
            break;
        }
        array_push($top_words, [$word, $count]);
        $i++;
    }
    return $top_words;
}

// fonctions mise en forme html
function liste_puce($words) {
    $output = "<ul>";
    foreach ($words as $word => $count) {
        $output .= "<li>$word ($count)</li>";
    }
    $output .= "</ul>";
    return $output;
}

function tableau($words) {
    $output = "<table border='1'>";
    $output .= "<tr><th>Word</th><th>Count</th></tr>";
    foreach ($words as $word => $count) {
        $output .= "<tr><td>$word</td><td>$count</td></tr>";
    }
    $output .= "</table>";
    return $output;
}
?>