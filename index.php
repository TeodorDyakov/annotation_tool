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
    <div id="main">
        <p>Напишете текста на анотацията във полето 'Label', след това натиснете Add new Label, за да запазите анотацията.</p>
        <button id = "show_btn" onclick="ButtonClick()">Show all labels</button>
        <div id="container">

            <div id = "form_container">
                <form action="labels_db.php" method="post">

                <label for = "label">Label</label>
                    <input type="text" name="label" id="label" value="example label" oninput = "UpdateLabelText()"><br>
                    
                    <label for = "X">X</label>
                    <input type="text" name="X" id="X" value = "0">

                    <label for = "label">Y</label>
                    <input type="text" name="Y" id="Y" value = "0">
                    
                    <button type="button" onclick="SaveLabel()">Add new label</button>
                </form>
            
            <div id = "msg_container">
                <p id = "message">Кликнете върху снимката или въведете координати!</p>
            </div>
        </div>

        <div id = "img_container">
            <?php
            echo "<img src='" . $_GET["imgId"] . "' id = 'imgToLabel'>";
            ?>
        </div>

        <div class="text-block" id="text">
            <p>example label</p>
        </div>

    </div>
</body>
<script src="js/script.js"></script>
<script type="text/javascript">
    var myImg = document.getElementById("imgToLabel");
    myImg.onmousedown = GetCoordinates;
</script>

</html>