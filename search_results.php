<html>

<head>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div class="topnav">
        <a class="active" href="home.php">Home</a>
        <div class="search-container">
            <form action="/action_page.php">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    <h3>Резултати от търсенето</h3>
    <?php
    $config = parse_ini_file('config/config.ini', true);

    $host = $config['db']['host'];
    $username = $config['db']['user'];
    $password = $config['db']['password'];

    //create the connection to db
    $conn = new PDO("mysql:host=$host;dbname=annotation_tool", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $term = $_GET["term"];

    $sql = "SELECT text, imgId FROM LABEL";

    $select_labels = $conn->prepare($sql);
    $select_labels->execute();
    $rows = $select_labels->fetchAll(PDO::FETCH_ASSOC);
    $results = array();

    echo "<ul>";
    foreach ($rows as $label) {
        if (strpos($label["text"], $term) && !in_array($label["imgId"], $results)) {
            array_push($results, $label["imgId"]);
            echo "<li><a href='http://localhost/annotation_tool/?imgId=" . $label["imgId"] . "'>" . $label["imgId"] . "</a></li>";
            echo "<img src = '" . $label["imgId"] . "'>";
        }
    }
    echo "</ul>";

    ?>

</body>

</html>