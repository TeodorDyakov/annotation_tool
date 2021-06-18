<?php
 $config = parse_ini_file('config/config.ini', true);

 $host = $config['db']['host'];
 $username = $config['db']['user'];
 $password = $config['db']['password'];

try {
  //create the connection to db
  $conn = new PDO("mysql:host=$host;dbname=annotation_tool", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //set the content-type for http response
  header('Content-Type: application/json');

  $requestUrl = $_SERVER['REQUEST_URI'];
  if (strpos($requestUrl, '/search')){
    $term = $_GET["term"];

        $sql = "SELECT text, imgId FROM LABEL";
    
        $select_labels = $conn->prepare($sql);
        $select_labels->execute();
        $rows = $select_labels->fetchAll(PDO::FETCH_ASSOC);
        $results = array();

        foreach($rows as $label){
            if(strpos($label["text"], $term)){
                array_push($results, $label["imgId"]);
            }
        }
        echo json_encode($results);
  } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //if get request, return all labels of the image with the given id
    //the imgId is a request paramemer from a GET request
    $imgId = $_GET["imgId"];
    
    $sql = "SELECT * FROM LABEL WHERE imgId = :imgId";
    
    $select_labels = $conn->prepare($sql);
    $select_labels->execute(array(':imgId' => $imgId));
    $rows = $select_labels->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);

  } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $imgId = $data["imgId"];

    $sql = "SELECT * FROM image WHERE imgId = :imgId";
    $check_if_image_exists = $conn->prepare($sql);

    $check_if_image_exists->execute(array(':imgId' => $imgId)) or die("failed!");
    $row = $check_if_image_exists->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      $sql = "INSERT INTO image (imgId) VALUES (:imgId)";
      $insert_image = $conn->prepare($sql);
      $insert_image->execute(array(':imgId' => $imgId)) or die("failed!");
    }

    $sql = "INSERT INTO label (text, x, y, imgId)
              VALUES (:label, :X, :Y, :imgId)";
    $insert_label = $conn->prepare($sql);
    $insert_label->execute($data) or die("failed!");
  }
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
