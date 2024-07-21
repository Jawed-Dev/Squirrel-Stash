<?php

use App\Mail\EmailSender;

    require_once './config.php';
    require_once './vendor/autoload.php';

    require_once './controller/ControllerStatistic.php';
    require_once './controller/ControllerUser.php';
    require_once './model/ModelMain.php';
    require_once './view/viewMain.php';
    
    require_once './util/HandlerJwt.php';
    require_once './util/HandlerLog.php';
    require_once './util/HandlerError.php';
    require_once './util/HandlerValidFormat.php';

    require_once './util/EmailSender.php';
    
    interface I_ControllerMain {
        // Controllers 
        function getControllerUser();
        function getControllerStatistic();
        // Handler JWT
        function getHandlerJwt();
        // Email
        function getEmailSender();
        // View Main
        function getViewMain();
        // Database
        function getDatabase();
        // Json
        function sendJsonResponse($data);
        function getRequestBodyJson();
        // Handler Error
        function getHandlerError();
        function getHandlerValidFormat();
        function validateDataForController($requireUserId = true);
        //function prepareDataForController($requireUserId = true, $requireBodyData = true );
        // cookies
        function createCookieStayConnected($stayConnect = false);
        function createCookieByRefreshToken($tokenJwt);
        
        // Prepare pages
        function authorizePage();
    }

    class ControllerMain implements I_ControllerMain {
        private $ModelMain;
        private $ControllerUser;
        private $ControllerStatistic;
        private $ViewMain;
        private $db;
        private $HandlerJwt;
        private $HandlerError;
        private $HandlerValidFormat;
        private $HandlerLog;
        private $EmailSender;

        // Controllers 
        /**
        * @return ControllerUser
        */
        public function getControllerUser() {
            if (!$this->ControllerUser) $this->ControllerUser = new ControllerUser();
            return $this->ControllerUser;
        }
        /**
        * @return ControllerStatistic
        */
        public function getControllerStatistic() {
            if (!$this->ControllerStatistic) $this->ControllerStatistic = new ControllerStatistic();
            return $this->ControllerStatistic;
        }

        // View 
        /**
        * @return ViewMain
        */
        public function getViewMain() {
            if (!$this->ViewMain) $this->ViewMain = new ViewMain();
            return $this->ViewMain;
        }

        // Database 
        /**
        * @return ModelMain
        */
        public function getModelMain() {
            if (!$this->ModelMain) $this->ModelMain = new ModelMain();
            return $this->ModelMain;
        }


        public function getDatabase() {
            if (!$this->db) $this->db = $this->getModelMain()->getConnection();
            return $this->db;
        }

        
        // Handler Jwt
        /**
        * @return HandlerJwt
        */
        public function getHandlerJwt() {
            if (!$this->HandlerJwt) $this->HandlerJwt = new HandlerJwt();
            return $this->HandlerJwt;
        }

        // Handler Error
        /**
        * @return HandlerError
        */
        public function getHandlerError() {
            if (!$this->HandlerError) $this->HandlerError = new HandlerError();
            return $this->HandlerError;
        }

        // Handler Valid format
        /**
        * @return HandlerValidFormat
        */
        public function getHandlerValidFormat() {
            if (!$this->HandlerValidFormat) $this->HandlerValidFormat = new HandlerValidFormat();
            return $this->HandlerValidFormat;
        }

        // HandlerLog
        /**
        * @return HandlerLog
        */
        public function HandlerLog() {
            if (!$this->HandlerLog) $this->HandlerLog = new HandlerLog();
            return $this->HandlerLog;
        }
        // EmailSender
        /**
        * @return EmailSender
        */
        public function getEmailSender() {
            if (!$this->EmailSender) $this->EmailSender = new EmailSender();
            return $this->EmailSender;
        }

        public function prepareDataForController($requireUserId = true, $requireBodyData = true ) {
            $bodyDataJson = null;
            $bodyData = null;
            if($requireBodyData) {
                $bodyDataJson = $this->getRequestBodyJson();
                $bodyData = json_decode($bodyDataJson, true);
                foreach ($bodyData as &$value) {
                    if (is_string($value) && $value) $this->getHandlerValidFormat()->sanitizeData($value);
                }
            }

            $db = $this->getDatabase();
            $userId = null;

            if($requireUserId) {
                $decodedJwt = $this->getHandlerJwt()->getJwtFromHeader();
                $isSessionActive = $this->getControllerUser()->isSessionActive($decodedJwt);
                if(!$isSessionActive) throw new Exception('Erreur prépare data');
                $userId = $this->getControllerUser()->getUserIdFromJwt($decodedJwt);
            }

            return [
                'bodyData' => $bodyData,
                'userId' => $userId,
                'dataBase' => $db
            ];
        }

        public function validateDataForController($requireUserId = true, $requireBodyData = true) {
            $dataRequest = $this->prepareDataForController($requireUserId, $requireBodyData);

            // $requireUserId = $params['requireUserId'];
            // $requireBodyData = $params['requireBodyData'];
            // $requireDb = $params['requireDatabase'];

            $dataRequire[] = $dataRequest['dataBase'];
            if($requireUserId) $dataRequire[] = $dataRequest['userId'];
            if($requireBodyData) {
                $dataRequire[] = $dataRequest['bodyData'];
                if(count($dataRequest['bodyData']) >= MAX_DATA_BODY_REQUEST) return null;
            }
            $isAnyMainDataEmpty = $this->getHandlerError()->verifyIfMainDataExists($dataRequire);
            if ($isAnyMainDataEmpty) return null;




            return $dataRequest;
        }

        public function createCookieStayConnected($stayConnect = false) {
            $valueCookie = ($stayConnect) ? 1 : 0;
            setcookie('stayConnected', $valueCookie, [
                'expires' => time() + TIME_EXPIRE_TIME_STAY_CONNECTED, 
                'httponly' => true,
                'secure' => true,
                'samesite' => 'Strict'
            ]);
            $_COOKIE['stayConnected'] = $stayConnect;
        }

        function createCookieByRefreshToken($tokenJwt) {
            setcookie('refreshToken', $tokenJwt, [
                'expires' => time() + TIME_EXPIRE_TIME_STAY_CONNECTED, 
                'httponly' => true,
                'secure' => true,
                'samesite' => 'Strict'
            ]);
            $_COOKIE['refreshToken'] = $tokenJwt;
        }

        public function sendJsonResponse($data) {
            echo json_encode($data);
        }

        public function getRequestBodyJson() {
            return file_get_contents('php://input');
        }

        // Prepare Pages
        public function authorizePage() {
            $decodedJwt = $this->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->getControllerUser()->isSessionActive($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ];  
            $this->getViewMain()->renderJson($dataPage);
        }
    }

?>