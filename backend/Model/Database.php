<?php
    namespace Model;
    use PDO;
    use PDOException;

    class Database {

        private static $connection = null;

        public static function getConnection() {

            if(!self::$connection) {
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "eco_projet";
        
                $connexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $connexion;
            }
            return self::$connection;

        }

    }

?>