<?php
require_once 'database.php';

$db = new Database();

//set the content-type for http response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $imgId = $_GET["imgId"];
  insertImageIfNotExists($imgId, $db);

  $query = $db->selectLabelsByImageIdQuery(array(':imgId' => $imgId));
  $rows = $query["data"]->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($rows);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $data = json_decode(file_get_contents('php://input'), true);
  $imgId = $data["imgId"];

  insertImageIfNotExists($imgId, $db);

  $db->insertLabelQuery($data);
}

function insertImageIfNotExists($imgId, $db){
  $query = $db->selectImageByIdQuery(array(':imgId' => $imgId));
  $row = $query["data"]->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    $db->insertImageQuery(array(':imgId' => $imgId));
  }
}