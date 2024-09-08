<?php

    use App\Mail\EmailSender;

    require_once './config.php';
    require_once './vendor/autoload.php';

    require_once './routers/RouterPage.php';
    require_once './routers/RouterGetData.php';
    require_once './routers/RouterAction.php';

    require_once './controllers/ControllerStatisticAction.php';
    require_once './controllers/ControllerStatisticGetData.php';
    require_once './controllers/ControllerUser.php';
    require_once './models/ModelMain.php';
    require_once './views/ViewMain.php';
    
    require_once './utils/HandlerJwt.php';
    require_once './utils/HandlerLog.php';
    require_once './utils/HandlerError.php';
    require_once './utils/HandlerValidFormat.php';

    require_once './utils/EmailSender.php';
    
    interface I_ControllerMain {
        // Routers
        function getRouterPage();
        // Controllers 
        function getControllerUser();
        function getControllerStatisticGetData();
        function getControllerStatisticAction();
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
        function getValidateDataForController($params);        
        // Prepare pages
        function authorizePage();
    }

    class ControllerMain implements I_ControllerMain {
        private $ModelMain;
        private $ControllerUser;
        private $ControllerStatisticAction;
        private $ControllerStatisticGetData;
        private $ViewMain;
        private $db;
        private $HandlerJwt;
        private $HandlerError;
        private $HandlerValidFormat;
        private $HandlerLog;
        private $EmailSender;
        private $RouterPage;
        private $RouterAction;
        private $RouterGetData;

        // Controllers 
        /**
        * @return ControllerUser
        */
        public function getControllerUser() {
            if (!$this->ControllerUser) $this->ControllerUser = new ControllerUser();
            return $this->ControllerUser;
        }


        /**
        * @return ControllerStatisticAction
        */
        public function getControllerStatisticAction() {
            if (!$this->ControllerStatisticAction) $this->ControllerStatisticAction = new ControllerStatisticAction();
            return $this->ControllerStatisticAction;
        }

        /**
        * @return ControllerStatisticGetData
        */
        public function getControllerStatisticGetData() {
            if (!$this->ControllerStatisticGetData) $this->ControllerStatisticGetData = new ControllerStatisticGetData();
            return $this->ControllerStatisticGetData;
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

        // Routers
        /**
        * @return RouterAction
        */
        public function getRouterAction() {
            if (!$this->RouterAction) $this->RouterAction = new RouterAction();
            return $this->RouterAction;
        }

        /**
        * @return RouterPage
        */
        public function getRouterPage() {
            if (!$this->RouterPage) $this->RouterPage = new RouterPage();
            return $this->RouterPage;
        }

        /**
        * @return RouterGetData
        */
        public function getRouterGetData() {
            if (!$this->RouterGetData) $this->RouterGetData = new RouterGetData();
            return $this->RouterGetData;
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

        public function getDataForController($requireAuth= true, $requireBodyData = true,  $requireDatabase = true, $allowForAllAuth = false) {
            $bodyDataJson = null;
            $bodyData = null;
            $db = null;
            $userId = null;

            if($requireBodyData) {
                $bodyDataJson = $this->getRequestBodyJson();
                $bodyData = json_decode($bodyDataJson, true);
                if(!$bodyDataJson) throw new Exception('Erreur de données.');
            }
            if($requireDatabase) {
                $db = $this->getDatabase();
                if(!$db) throw new Exception('Erreurs de données. 1 ');
            }
            
            if(!$allowForAllAuth) {
                if($requireAuth) {
                    $decodedJwt = $this->getHandlerJwt()->getJwtFromHeader();
                    $isSessionActive = $this->getControllerUser()->isSessionActive($decodedJwt);
                    if(!$isSessionActive) throw new Exception('Erreurs de données. 2');
                    $userId = $this->getControllerUser()->getUserIdFromJwt($decodedJwt);
                    if(!$userId) throw new Exception('Erreurs de données. 2');
                }
                else {
                    $decodedJwt = $this->getHandlerJwt()->getJwtFromHeader();
                    $isSessionActive = $this->getControllerUser()->isSessionActive($decodedJwt);
                    if($isSessionActive) throw new Exception('Erreurs de données. 3');
                }
            }

            return [
                'bodyData' => $bodyData,
                'userId' => $userId,
                'dataBase' => $db
            ];
        }

        public function getValidateDataForController($params) {
            // Optionals Params with default value
            $requireAuth = $params['requireAuth'] ?? true;
            $requireBodyData = $params['requireBodyData'] ?? true;
            $requireDatabase = $params['requireDatabase'] ?? true;
            $functionValidData = $params['functionValidData'] ?? null;

            // for methods which are used for connected user and not connected like overlay support
            $allowForAllAuth = $params['allowForAllAuth'] ?? false;

            // get Data for a Controller
            $dataRequest = $this->getDataForController($requireAuth, $requireBodyData, $requireDatabase, $allowForAllAuth);
        
            // option database
            if($requireDatabase) $dataRequire[] = $dataRequest['dataBase'];
            // params userId
            if($requireAuth && !$allowForAllAuth) $dataRequire[] = $dataRequest['userId'];
            // params bodydata
            if($requireBodyData) {
                $dataRequire[] = $dataRequest['bodyData'];
            }
            // Verify the presence of essential data
            $isAnyMainDataEmpty = $this->getHandlerError()->verifyIfMainDataExists($dataRequire);
            if ($isAnyMainDataEmpty) return throw new Exception('Requête invalide.');

            // Verify the expected format of data 
            if($functionValidData) {   
                $isAnyError = $this->getHandlerError()->$functionValidData($dataRequest);
                if ($isAnyError) return throw new Exception('Requête invalide.');
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