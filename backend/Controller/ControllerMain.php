<?php

    require_once './vendor/autoload.php';
    require_once './view/viewMain.php';
    require_once './config.php';
    
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    interface I_ControllerMain {
        // Controllers
        function getControllerUser();
        function getControllerStatistic();
        // view
        function getViewMain();
        // Database
        function getDatabase();
        // Jeton / Session
        function createTokenJwt($userId);
        function isValidTokenJwt($tokenJwt);
        function decodeJwt($tokenJwt);
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
        }

        // Controllers lazy loading
        /**
        * @return ControllerUser
        */
        public function getControllerUser() {
            if (!$this->ControllerUser) $this->ControllerUser = $this->ContainerServices->getService('ControllerUser');
            return $this->ControllerUser;
        }
        /**
        * @return ControllerUser
        */
        public function getControllerStatistic() {
            if (!$this->ControllerStatistic) $this->ControllerStatistic = $this->ContainerServices->getService('ControllerStatistic');
            return $this->ControllerStatistic;
        }

        // View lazy loading
        public function getViewMain() {
            if (!$this->ViewMain) {
                $this->ViewMain = new ViewMain();
            }
            return $this->ViewMain;
        }

        // Database
        /**
        * @return Database
        */
        public function getDatabase() {
            if (!$this->db) $this->db = $this->ContainerServices->getService('Database');
            return $this->db;
        }

        // JWT Functions 
        public function createTokenJwt($userId) {
            $key = JWT_SECRET_KEY; 
            $issuedAt = time();
            $expirationTime = $issuedAt + TIME_EXPIRE_TIME_JWT;  
            $issuer = BASE_URL;
            //$jti = bin2hex(random_bytes(16)); 
            $payload = array(
                'userId' => $userId,
                'issuedAt' => $issuedAt,
                'expireTime' => $expirationTime,
                'issuer' => $issuer,
                //'jti' => $jti
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
            $timeNow = time();

            if(!empty($decodedJwt)) {
                // value empty
                if(empty($decodedJwt->userId)) return false;
                if(empty($decodedJwt->issuedAt)) return false;
                if(empty($decodedJwt->expireTime)) return false;

                // verif url back
                if($decodedJwt->issuer !== BASE_URL) return false;

                // time
                if(time() >= $decodedJwt->expireTime) return false;
                if($decodedJwt->issuedAt > $timeNow) return false;
                if($decodedJwt->expireTime < $timeNow) return false;

                // is int
                if(!is_int(($decodedJwt->userId))) return false;
                if(!is_int(($decodedJwt->expireTime))) return false;
                if(!is_int(($decodedJwt->issuedAt))) return false;
                
                return true;
            }
            return false;
        }
        function getBearerTokenJwt() {
            if (!isset($_SERVER['HTTP_AUTHORIZATION'])) return null;
            if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) return $matches[1];
            return null;
        }

        // json 
        function sendJsonResponse($data) {
            echo json_encode($data);
        }

        // Prepare Pages
        public function preparePageIndex($tokenJwt) {
            $isUserConnected = $this->getControllerUser()->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ];  
            $this->getViewMain()->renderPageIndexJson($dataPage);
        }
    }

?>