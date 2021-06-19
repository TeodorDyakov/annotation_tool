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


    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>

    <script>
        
    </script>
</body>

</html>