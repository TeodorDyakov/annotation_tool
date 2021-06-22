<html>

<head>
    <link rel="stylesheet" href="styles/style.css">
</head>

<?php include 'header.html'; ?>

<body>

    <div id="main">
        <form action="label_page.php" method="GET">
            Enter URL of image you wish to annotate:
            <input type="text" name="imgId" id="imgId" placeholder="http://www.example.com/cat.jpeg">
            <input type="submit" value="Annotate Image" name="submit">
        </form>
        <p id="browseP">Or browse through uploaded images:</p>
    </div>

    <div class="gallery">

        <?php
        require_once 'database.php';

        $db = new Database();

        $query = $db->selectAllImageIDs();
        $rows = $query["data"]->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            echo "<a href='http://localhost/annotation_tool/?imgId=" . $row["imgId"] . "'>";
            echo "<img src = '" . $row["imgId"] . "'>";
            echo "</a>";
        }

        ?>

    </div>

</body>

</html>