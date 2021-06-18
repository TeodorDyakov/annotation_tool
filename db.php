<?php
    class Database {
        private $connection;
        private $selectLabels;
        private $selectImage;
        private $insertImage;
        private $insertLabel;

        public function __construct() {
            $config = parse_ini_file('config/config.ini', true);

            $type = $config['db']['type'];
            $host = $config['db']['host'];
            $name = $config['db']['name'];
            $user = $config['db']['user'];
            $password = $config['db']['password'];

            $this->init($type, $host, $name, $user, $password);
        }

        private function init($type, $host, $name, $user, $password) {
            try {
                $this->connection = new PDO("$type:host=$host;dbname=$name", $user, $password,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

                $this->prepareStatements();
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        private function prepareStatements() {
            $sql = "INSERT INTO label (id, text, x, y, imgId)
              VALUES (NULL, :label_text, :x, :y, :imgId)";
            $this->insertLabel = $this->connection->prepare($sql);

            $sql = "SELECT * FROM LABEL WHERE imgId = :imgId";
            $this->selectLabels = $this->connection->prepare($sql);

            $sql = "SELECT * FROM image WHERE imgId = :imgId";
            $this->selectImage = $this->connection->prepare($sql);

            $sql = "INSERT INTO image (imgId) VALUES (:imgId)";
            $this->insertImage = $this->connection->prepare($sql);
        }

        public function selectImageByIdQuery($data) {
            try {
                $this->selectImage->execute($data);
                return ["success" => true];
            } catch(PDOException $e) {
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectLabelsForImageQuery($data) {
            try {
                $this->selectLabels->execute($data);
                return ["success" => true];
            } catch(PDOException $e) {
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        // $data -> ["label_text" => value, "xy" => value, y => value, imgId => value]
        public function insertLabelQuery($data) {
            try {
                $this->insertLabel->execute($data);
                return ["success" => true];
            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function insertImageQuery($data) {
            try {
                $this->insertImage->execute($data);
                return ["success" => true];
            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }
       
        function __destruct() {
            $this->connection = null;
        }
    }
?>