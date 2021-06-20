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
    
    <div id = "main">
        <form action="index.php" method="GET">
            Enter URL of image you wish to annotate:
            <input type="text" name="imgId" id="imgId">
            <input type="submit" value="Annotate Image" name="submit">
        </form>
        <p id = "browseP">Or browse through uploaded images:</p>
    </div>

    <div class = "gallery">
    
    <?php
    $config = parse_ini_file('config/config.ini', true);

    $host = $config['db']['host'];
    $username = $config['db']['user'];
    $password = $config['db']['password'];

    $conn = new PDO("mysql:host=$host;dbname=annotation_tool", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT imgId FROM image";

    $select_labels = $conn->prepare($sql);
    $select_labels->execute();
    $rows = $select_labels->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        echo "<a href='http://localhost/annotation_tool/?imgId=" . $row["imgId"] . "'>";
        echo "<img src = '" . $row["imgId"] . "'>";
        echo "</a>";
    }

    ?>

    </div>

</body>

</html>