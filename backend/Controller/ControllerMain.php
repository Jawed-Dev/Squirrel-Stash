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
        function prepareAndValidateData($requireUserId = true);
        
        // Prepare pages
        function authorizePageIndex();
    }

    class ControllerMain implements I_ControllerMain {
        private $ModelMain;
        private $ControllerUser;
        private $ControllerStatistic;
        private $ViewMain;
        private $db;
        private $HandlerJwt;
        private $HandlerError;
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
            if (!$this->HandlerError) $this->HandlerError = new HandlerValidFormat();
            return $this->HandlerError;
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


        // json 

        public function prepareAndValidateData($requireUserId = true) {
            $dataRequest = $this->getHandlerJwt()->prepareDataForModel($requireUserId);
            $dataRequire = [ $dataRequest['dataBase'], $dataRequest['bodyData'] ];
            if ($requireUserId) $dataRequire[] = $dataRequest['userId'];

            if(count($dataRequest['bodyData']) >= MAX_DATA_BODY_REQUEST) return null;
        
            $isAnyError = $this->getHandlerError()->verifyDataForModel($dataRequire);
            if ($isAnyError) {
                throw new Exception('Erreur: Données invalides.'); 
                //return null;
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
        public function authorizePageIndex() {
            $decodedJwt = $this->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->getControllerUser()->isSessionActiveByJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ];  
            $this->getViewMain()->renderPageIndexJson($dataPage);
        }
    }

?>