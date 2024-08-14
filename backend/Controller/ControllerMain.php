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
        function validateDataForController($params);        
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

        // Database 
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

        public function prepareDataForController($requireAuth= true, $requireBodyData = true,  $requireDatabase = true) {
            $bodyDataJson = null;
            $bodyData = null;
            $db = null;
            $userId = null;

            if($requireBodyData) {
                $bodyDataJson = $this->getRequestBodyJson();
                $bodyData = json_decode($bodyDataJson, true);
                // foreach ($bodyData as &$value) {
                //     if(is_string($value) && $value) $this->getHandlerValidFormat()->sanitizeData($value);
                // }
                if(!$bodyDataJson) throw new Exception('Erreur de données.');
            }
            if($requireDatabase) {
                $db = $this->getDatabase();
                if(!$db) throw new Exception('Erreurs de données.');
            }
            
            if($requireAuth) {
                $decodedJwt = $this->getHandlerJwt()->getJwtFromHeader();
                $isSessionActive = $this->getControllerUser()->isSessionActive($decodedJwt);
                if(!$isSessionActive) throw new Exception('Erreurs de données.');
                $userId = $this->getControllerUser()->getUserIdFromJwt($decodedJwt);
                if(!$userId) throw new Exception('Erreurs de données.');
            }
            else {
                $decodedJwt = $this->getHandlerJwt()->getJwtFromHeader();
                $isSessionActive = $this->getControllerUser()->isSessionActive($decodedJwt);
                if($isSessionActive) throw new Exception('Erreurs de données.');
            }

            return [
                'bodyData' => $bodyData,
                'userId' => $userId,
                'dataBase' => $db
            ];
        }

        public function validateDataForController($params) {
            // Optionals Params with default value
            $requireAuth = $params['requireAuth'] ?? true;
            $requireBodyData = $params['requireBodyData'] ?? true;
            $requireDatabase = $params['requireDatabase'] ?? true;
            $functionValidData = $params['functionValidData'] ?? null;

            // get Data for a Controller
            $dataRequest = $this->prepareDataForController($requireAuth, $requireBodyData, $requireDatabase);
        
            // option database
            if($requireDatabase) $dataRequire[] = $dataRequest['dataBase'];
            // params userId
            if($requireAuth) $dataRequire[] = $dataRequest['userId'];
            // params bodydata
            if($requireBodyData) {
                $dataRequire[] = $dataRequest['bodyData'];
            }
            // Verify the presence of essential data
            $isAnyMainDataEmpty = $this->getHandlerError()->verifyIfMainDataExists($dataRequire);
            if ($isAnyMainDataEmpty) return throw new Exception('Erreurs dans la requête.');

            // Verify the expected format of data 
            if($functionValidData) {   
                $isAnyError = $this->getHandlerError()->$functionValidData($dataRequest);
                if ($isAnyError) return throw new Exception('Erreurs dans la requête.');
            }
            return $dataRequest;
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