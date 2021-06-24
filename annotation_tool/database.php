<?php
class Database
{
  private $connection;
  private $select_labels;
  private $select_labels_by_imgId;
  private $insert_label;
  private $select_image_by_id;
  private $insert_image;
  private $select_images;

  public function __construct()
  {
    $config = parse_ini_file('config/config.ini', true);

    $type = $config['db']['type'];
    $host = $config['db']['host'];
    $name = $config['db']['name'];
    $user = $config['db']['user'];
    $password = $config['db']['password'];

    $this->init($type, $host, $name, $user, $password);
  }

  private function init($type, $host, $name, $user, $password)
  {
    try {
      $this->connection = new PDO(
        "$type:host=$host;dbname=$name",
        $user,
        $password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );

      $this->prepareStatements();
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  private function prepareStatements()
  {
    $sql = "SELECT text, imgId FROM LABEL";
    $this->select_labels = $this->connection->prepare($sql);

    $sql = "SELECT * FROM LABEL WHERE imgId = :imgId";
    $this->select_labels_by_imgId = $this->connection->prepare($sql);

    $sql = "INSERT INTO label (text, x, y, imgId, css)
          VALUES (:label, :X, :Y, :imgId, :css)";
    $this->insert_label = $this->connection->prepare($sql);

    $sql = "SELECT * FROM image WHERE imgId = :imgId";
    $this->select_image_by_id = $this->connection->prepare($sql);

    $sql = "INSERT INTO image (imgId) VALUES (:imgId)";
    $this->insert_image = $this->connection->prepare($sql);

    $sql = "SELECT (imgId) FROM image";
    $this->select_images = $this->connection->prepare($sql);
  }

  public function selectAllLabelsQuery()
  {
    try {
      $this->select_labels->execute();

      return ["success" => true, "data" => $this->select_labels];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function selectLabelsByImageIdQuery($data)
  {
    try {
      $this->select_labels_by_imgId->execute($data);

      return ["success" => true, "data" => $this->select_labels_by_imgId];
    } catch (PDOException $e) {
      $this->connection->rollBack();
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function insertLabelQuery($data)
  {
    try {
      $this->insert_label->execute($data);

      return ["success" => true];
    } catch (PDOException $e) {
      $this->connection->rollBack();
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  // $data -> ["fn" => value, "mark" => value]
  public function selectImageByIdQuery($data)
  {
    try {
      $this->select_image_by_id->execute($data);

      return ["success" => true, "data" => $this->select_image_by_id];
    } catch (PDOException $e) {
      $this->connection->rollBack();
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function insertImageQuery($data)
  {
    try {
      $this->insert_image->execute($data);

      return ["success" => true];
    } catch (PDOException $e) {
      $this->connection->rollBack();
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function selectAllImageIDs()
  {
    try {
      $this->select_images->execute();

      return ["success" => true, "data" => $this->select_images];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  /**
   * Close the connection to the DB
   */
  function __destruct()
  {
    $this->connection = null;
  }
}
