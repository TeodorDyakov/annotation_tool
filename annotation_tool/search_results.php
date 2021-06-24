<html>

<head>
    <link rel="stylesheet" href="styles/style.css">
</head>

    <?php include 'header.html'; ?>

    <body>

    <h3>Резултати от търсенето:</h3>
    <?php
    require_once 'database.php';
    
    $db = new Database();

    $term = $_GET["term"];

    if(!$term){
        return;
    }

    $select_labels = $db->selectAllLabelsQuery();
    $rows = $select_labels["data"]->fetchAll(PDO::FETCH_ASSOC);
    $results = array();

    echo "<ul>";
    foreach ($rows as $label) {
        if (strpos($label["text"], $term) !== false && !in_array($label["imgId"], $results)) {
            array_push($results, $label["imgId"]);
            $text = $label["text"];

            $text = preg_replace('/(\S*'. $term .'\S*)/i', '<b>$1</b>', $text);

            echo "<li><a href='label_page.php?imgId=" . $label["imgId"] . "'>" . $label["imgId"] . "</a></li>";
            echo "<p>". $text ."</p>";
            echo "<img class = 'searchRes' src = '" . $label["imgId"] . "'>";
        }
    }
    echo "</ul>";

    ?>

</body>

</html>