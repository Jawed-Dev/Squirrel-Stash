<?php

    require_once './controller/ControllerMain.php';
    require_once './key.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    interface I_HandlerJwt {
        // Main Controller lazy loading
        function getControllerMain();
        // Jeton / Session
        function createTokenJwt($userId);
        function isValidTokenJwt($decodedJwt);
        function decodeJwt($tokenJwt);
        function getJwtFromHeader();
        function prepareDataForModel();
    }

    class HandlerJwt implements I_HandlerJwt {
        private $ControllerMain;
        // Controller Main lazy loading
        /**
        * @return ControllerMain
        */
        // Model ControllerMain lazyloading
        public function getControllerMain() {
            if (!$this->ControllerMain) $this->ControllerMain = new ControllerMain();
            return $this->ControllerMain;
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
        public function prepareDataForModel() {
            $TokenJwtFromHeader = $this->getJwtFromHeader();
            $decodedJwt = $this->decodeJwt($TokenJwtFromHeader);

            $isSessionActive = $this->getControllerMain()->getControllerUser()->isSessionActiveJwt($decodedJwt);
            if(!$isSessionActive) return null;

            $userId = $this->getControllerMain()->getControllerUser()->getUserIdFromJwt($decodedJwt);
            
            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);
            
            return [
                'bodyData' => $data,
                'userId' => $userId
            ];
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
        function getJwtFromHeader() {
            if (!isset($_SERVER['HTTP_AUTHORIZATION'])) return null;
            if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) return $matches[1];
            return null;
        }
    }
?>