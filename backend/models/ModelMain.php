<?php
    //namespace model;
    require_once './config.php';
    use PDO;
    use PDOException;
    
    interface I_ModelMain {
        static function getConnection();
        function createRandomHashSha256();
    }

    class ModelMain implements I_ModelMain {
        private static $connection = null;
        public static function getConnection() {
            if(!self::$connection) {
                $servername = "localhost";
                $username = $_ENV['USER_DB']; 
                $password = $_ENV['USER_DB_KEY'];
                $dbname = "squirrel_stash";
                $connexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $connexion;
            }
            return self::$connection;
        }

        public function createRandomHashSha256() {
            $randomBytes = random_bytes(32); 
            return hash('sha256', $randomBytes); 
        }

    }

?>