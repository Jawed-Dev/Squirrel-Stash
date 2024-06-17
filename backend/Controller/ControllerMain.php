<?php

    require_once './vendor/autoload.php';
    require_once './View/ViewMain.php';
    require_once './config.php';
    
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    interface I_ControllerMain {
        // Controllers
        function getControllerUser();
        function getControllerStatistic();
        // Database
        function getDatabase();
        // Jeton / Session
        function createTokenJwt($userId);
        function isValidTokenJwt($tokenJwt);
        function decodeJwt($tokenJwt);
        function getUserIdIntoJwt($decodedJwt);
        function getBearerTokenJwt();
        // Prepare pages
        function preparePageIndex($tokenJwt);
    }

    class ControllerMain implements I_ControllerMain {
        private $ContainerServices;
        private $ControllerUser;
        private $ControllerStatistic;
        private $ViewMain;
        private $db;

        public function __construct($ContainerServices) {
            $this->ContainerServices = $ContainerServices;
            $this->ViewMain = new ViewMain();
        }

        // Controllers lazy loading
        /**
        * @return ControllerUser
        */
        public function getControllerUser() {
            if (!$this->ControllerUser) $this->ControllerUser = $this->ContainerServices->get('ControllerUser');
            return $this->ControllerUser;
        }
        /**
        * @return ControllerUser
        */
        public function getControllerStatistic() {
            if (!$this->ControllerStatistic) $this->ControllerStatistic = $this->ContainerServices->get('ControllerStatistic');
            return $this->ControllerStatistic;
        }

        // Database
        /**
        * @return Database
        */
        public function getDatabase() {
            if (!$this->db) $this->db = $this->ContainerServices->get('Database');
            return $this->db;
        }

        // JWT Functions 
        public function createTokenJwt($userId) {
            $key = JWT_SECRET_KEY; 
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;  
            $payload = array(
                'userId' => $userId,
                'issuedAt' => $issuedAt,
                'expireTime' => $expirationTime
            );

            $jwt = JWT::encode($payload, $key, 'HS256');
            return $jwt;
        }
        public function decodeJwt($tokenJwt) {
            $key = JWT_SECRET_KEY; 
            $decodedJwt = JWT::decode($tokenJwt, new Key($key, 'HS256'));
            return $decodedJwt;
        }
        public function isValidTokenJwt($decodedJwt) {
            if(!empty($decodedJwt)) {
                if(empty($decodedJwt->userId)) return false;
                if(empty($decodedJwt->issuedAt)) return false;
                if(empty($decodedJwt->expireTime)) return false;

                if(time() >= $decodedJwt->expireTime) return false;
                if(!is_int(($decodedJwt->userId))) return false;
                
                return true;
            }
            return false;
        }
        public function getUserIdIntoJwt($decodedJwt) {
            return $decodedJwt->userId;
        }
        function getBearerTokenJwt() {
            if (!isset($_SERVER['HTTP_AUTHORIZATION'])) return null;
            if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) return $matches[1];
            return null;
        }

        // Prepare Pages
        public function preparePageIndex($tokenJwt) {
            $isUserConnected = $this->getControllerUser()->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ];  
            $this->ViewMain->renderPageIndexJson($dataPage);
        }
    }

?>