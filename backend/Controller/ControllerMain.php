<?php
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
    
    interface I_ControllerMain {
        // Controllers 
        function getControllerUser();
        function getControllerStatistic();
        // Handler JWT
        function getHandlerJwt();
        // view Main
        function getViewMain();
        // Database
        function getDatabase();
        // json
        function sendJsonResponse($data);
        function getRequestBodyJson();
        // Handler Error
        function getHandlerError();
        function getHandlerValidFormat();
        // Prepare pages
        function authorizePageIndex();
    }

    class ControllerMain implements I_ControllerMain {
        private $ControllerUser;
        private $ControllerStatistic;
        private $ViewMain;
        private $db;
        private $HandlerJwt;
        private $HandlerError;
        private $HandlerLog;

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
        public function getDatabase() {
            if (!$this->db) $this->db = new ModelMain();
            return $this->db->getConnection();
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


        // json 
        public function sendJsonResponse($data) {
            echo json_encode($data);
        }

        public function getRequestBodyJson() {
            return file_get_contents('php://input');
        }

        // Prepare Pages
        public function authorizePageIndex() {
            $tokenJwt = $this->getHandlerJwt()->getJwtFromHeader();
            if(!$tokenJwt) $tokenJwt = '';
            $decodedJwt = $this->getHandlerJwt()->decodeJwt($tokenJwt);
            $isSessionActive = $this->getControllerUser()->isSessionActiveJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ];  
            $this->getViewMain()->renderPageIndexJson($dataPage);
        }
    }

?>