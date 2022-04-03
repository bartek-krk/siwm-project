<?php
    class DbManager {
        private $conn;

        public function __construct($configuration) {
            $host = $configuration->getProperty('db.host', '');
            $user = $configuration->getProperty('db.user', '');
            $password = $configuration->getProperty('db.password', '');
            $schema = $configuration->getProperty('db.schema', '');
            $this->conn = mysqli_connect($host, $user, $password, $schema);
        }

        public function executeQuery($query) {
            return mysqli_query($this->conn, $query);
        }

        public function __destruct() {
            $this->conn->close();
        }
    }
?>