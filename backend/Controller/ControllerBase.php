<?php

    require_once './vendor/autoload.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    interface I_ControllerBase {
        function renderPageIndex($bearerJwt);
        function createJwtUser($userId);
        function isValidToken($tokenJwt);
        function decodeJwt($tokenJwt);
        function getUserIdIntoToken($decodedJwt);
    }

    class ControllerBase implements I_ControllerBase {

        private $Container;
        private $ControllerUser;
        private $ControllerStatistic;
        
        public function __construct($Container) {
            $this->Container = $Container;
        }

        // ====== Controllers lazy loadings 

        public function getControllerUser() {
            if (!$this->ControllerUser) $this->ControllerUser = $this->Container->get('ServiceUser');
            return $this->ControllerUser;
        }

        // ================================

        function jsonVarDump($variable) {
            ob_start();
            var_dump($variable);
            $output = ob_get_clean();
            return json_encode(['debug' => $output]);
        }

        public function renderPageIndex($bearerJwt) {
            $isUserConnected = $this->getControllerUser()->isUserConnected($bearerJwt);
            //var_dump($isUserConnected);
            echo json_encode([
                'isUserConnected' => $isUserConnected,
                'testVal' => 2,
            ]);
        }

        public function createJwtUser($userId) {
            $key = 'votre_cle_secrete'; 
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
            $key = 'votre_cle_secrete'; 
            $decodedJwt = JWT::decode($tokenJwt, new Key($key, 'HS256'));
            return $decodedJwt;
        }

        public function isValidToken($decodedJwt) {
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

        public function getUserIdIntoToken($decodedJwt) {
            return $decodedJwt->userId;
        }

        function getBearerToken() {
            if (!isset($_SERVER['HTTP_AUTHORIZATION'])) return null;
            if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) return $matches[1];
            return null;
        }
    }

?>