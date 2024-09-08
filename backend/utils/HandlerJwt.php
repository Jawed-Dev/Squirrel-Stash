<?php

    require_once './controllers/ControllerMain.php';
    //require_once './key.php';
    require_once './config.php';

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
        public function createTokenJwt($userId, $stayConnect = false) {
            $key = $_ENV['JWT_SECRET_KEY']; 
            $issuedAt = time();
            $timeExpiration = ($stayConnect) ? TIME_EXPIRE_TIME_STAY_CONNECT : TIME_EXPIRE_TIME_REFRESH_JWT;
            $expirationTime = $issuedAt + $timeExpiration;  
            $issuer = BASE_URL;

            //$jti = bin2hex(random_bytes(16)); 
            $payload = array(
                'userId' => $userId,
                'issuedAt' => $issuedAt,
                'expireTime' => $expirationTime,
                'issuer' => $issuer,
                //'jti' => $jti
            );
            try {
                $jwt = JWT::encode($payload, $key, 'HS256');
                return $jwt;
            }
            catch (Exception $e) {
                // log
                return throw new Exception('Erreurs de données. 4');
            }
        }

        public function decodeJwt($tokenJwt) {
            try {
                $key = $_ENV['JWT_SECRET_KEY']; 
                $decodedJwt = JWT::decode($tokenJwt, new Key($key, 'HS256'));
                return $decodedJwt;
            }
            catch (Exception $e) {
                return throw new Exception('Erreurs de données. 12');
            }
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
            if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) return null;
            $tokenJwt = $matches[1];
            $decodedJwt = $this->decodeJwt($tokenJwt);
            if(!$decodedJwt) return null;
            return $decodedJwt;
        }
    }
?>