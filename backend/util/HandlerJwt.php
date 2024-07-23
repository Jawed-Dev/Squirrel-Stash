<?php

    require_once './controller/ControllerMain.php';
    require_once './key.php';
    require_once './config.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    interface I_HandlerJwt {
        // Main Controller lazy loading
        function getControllerMain();
        // Jeton / Session
        function createAccessTokenJwt($userId);
        function isValidTokenJwt($decodedJwt);
        function decodeJwt($tokenJwt);
        function getJwtFromHeader();
        function createRefreshTokenJwt($userId);
        function getNewAccessToken();
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
        public function createAccessTokenJwt($userId) {
            $key = JWT_SECRET_KEY; 
            $issuedAt = time();
            $timeExpiration = TIME_EXPIRE_TIME_ACCESS_JWT;
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
                return throw new Exception('Erreurs de données.');
            }
        }

        public function getNewAccessToken() {
            if(!empty($_COOKIE['refreshToken'])) {
                $currentRefreshToken = $_COOKIE['refreshToken'];
                $decodedRefreshJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($currentRefreshToken);
                $isValidToken = $this->getControllerMain()->getHandlerJwt()->isValidTokenJwt($decodedRefreshJwt);
                if(!$isValidToken) return throw new Error('Erreur de donnée');
                $userId = $this->getControllerMain()->getControllerUser()->getUserIdFromJwt($decodedRefreshJwt);
                $newTokenJwt = $this->getControllerMain()->getHandlerJwt()->createAccessTokenJwt($userId);

                //$tokenJwt = $newTokenJwt;
                //$newTokenRefreshJwt = $this->getControllerMain()->getHandlerJwt()->createRefreshTokenJwt($userId);
                //$this->getControllerMain()->getHandlerJwt()->createCookieByRefreshToken($newTokenRefreshJwt);

                if(!$newTokenJwt) return throw new Error('Erreur de donnée');
                return $newTokenJwt;
            }
            return null;
        }

        public function createRefreshTokenJwt($userId, $stayConnected = false) {
            $key = JWT_SECRET_KEY; 
            $issuedAt = time();
            if(empty($_COOKIE['stayConnected'])) {
                $this->getControllerMain()->createCookieStayConnected(0);
            }
            $timeExpiration = ($_COOKIE['stayConnected']) ? TIME_EXPIRE_TIME_STAY_CONNECTED : TIME_EXPIRE_TIME_REFRESH_JWT;
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
                return throw new Exception('Erreurs de données.');
            }
        }

        public function decodeJwt($tokenJwt) {
            try {
                $key = JWT_SECRET_KEY; 
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