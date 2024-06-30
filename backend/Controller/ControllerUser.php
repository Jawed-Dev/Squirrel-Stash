<?php 

    require_once './controller/ControllerMain.php';
    require_once './model/modelUser.php';
    require_once './view/viewUser.php';
    require_once './config.php';

    interface I_ControllerUser {
        // Main Controller
        function getControllerMain();
        // Model User
        function getModelUser();
        // View 
        function getViewUser();
        // Auth
        function fetchInsertUser();
        function isSessionActiveJwt($decodedJwt);
        function handleSuccessLogin();
        function getUserIdFromJwt($decodedJwt);
        function getStateSession();
        function FetchSendResetPassToken();
        function fetchUpdatePassword();
        function fetchIsValidResetPassToken();
        // Prepare pages
        function authorizePageLogin();
        function authorizePageForgotPass();
        function authorizePageRegister();
        function authorizePageResetPassword();
    }

    class ControllerUser implements I_ControllerUser {
        private $ControllerMain;
        private $ModelUser;
        private $ViewUser;
        
        // Controller Main
        /**
        * @return ControllerMain
        */
        // Model ControllerMain 
        public function getControllerMain() {
            if (!$this->ControllerMain) $this->ControllerMain = new ControllerMain();
            return $this->ControllerMain;
        }

        // Model User
        public function getModelUser() {
            if (!$this->ModelUser) $this->ModelUser = new ModelUser();
            return $this->ModelUser;
        }

        // View 
        public function getViewUser() {
            if (!$this->ViewUser) $this->ViewUser = new ViewUser();
            return $this->ViewUser;
        }

        // Auth
        public function getUserIdFromJwt($decodedJwt) {
            return !empty($decodedJwt->userId) ? $decodedJwt->userId : null;
        }


        private function getUrlToResetPass($tokenResetPass) {
            return FRONT_BASE_URL."/reinitialiser-mot-de-passe?token=".$tokenResetPass;
        }

        public function getStateSession() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            if(!$decodedJwt) return $this->getControllerMain()->sendJsonResponse(['isSessionActive' => false]);
            $isSessionActive = $this->isSessionActiveJwt($decodedJwt);
            $this->getControllerMain()->sendJsonResponse(['isSessionActive' => $isSessionActive]);
        }

        public function isSessionActiveJwt($decodedJwt) {
            if(!$decodedJwt) return false;
            $isValidDecodedJwt = $this->getControllerMain()->getHandlerJwt()->isValidTokenJwt($decodedJwt);
            if(!$isValidDecodedJwt) return false;
            $userId = $this->getUserIdFromJwt($decodedJwt);
            $db = $this->getControllerMain()->getDatabase();
            $isUserExistFromId = $this->getModelUser()->isUserExistFromId($db, $userId);
            return $isUserExistFromId ;
        }

        public function handleSuccessLogin() {
            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);
            if(empty($data)) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);
            
            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getModelUser()->getUserIdFromLogin($db, $data);
            if(!$userId) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);

            $tokenJwt = null;
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->createTokenJwt($userId);
            $this->getControllerMain()->sendJsonResponse(['tokenJwt' => $tokenJwt]);
        }

        public function fetchInsertUser() {
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $db = $dataRequest['dataBase'];
            
            $isAnyError = false; //$this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelUser()->insertUser($db, $dataRequest['bodyData']);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchIsValidResetPassToken() {
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $db = $dataRequest['dataBase'];
            
            $isAnyError = false; //$this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $isSuccessReq = false;
            if($isAnyError) $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
            $isSuccessReq = $this->getModelUser()->isValidResetPassToken($db, $dataRequest['bodyData']);
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function FetchSendResetPassToken() {
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $db = $dataRequest['dataBase'];
            
            //var_dump($dataRequest);
            $isAnyError = false; //$this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $isSuccessReq = false;
            if($isAnyError) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            $dataBody = $dataRequest['bodyData'];
            $isLastTokenDeleted = $this->getModelUser()->deleteResetPassTokenByEmail($db, $dataBody);

            $uniqueTokenResetPass = $this->getModelUser()->getUniqueTokenResetPass($db);
            if(empty($uniqueTokenResetPass)) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
            $dataRequest['bodyData']['resetPassToken'] = $uniqueTokenResetPass;

            $isSuccessReqDb = $this->getModelUser()->insertResetPassToken($db, $dataRequest);
            if(!$isSuccessReqDb) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            //var_dump($dataRequest);
            $requestUrl = $this->getUrlToResetPass($dataRequest['bodyData']['resetPassToken']); 
            $params = [
                'email' => $dataBody['email'],
                'urlToResetPass' => $requestUrl,
            ];
            //var_dump($params);
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailResetPass($params);
            $isSuccessReq = $isSuccessReqDb && $isSuccessReqEmail;
            

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function fetchUpdatePassword() {
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $db = $dataRequest['dataBase'];
            //var_dump($dataRequest);
            $isAnyError = false; //$this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $isSuccessReq = false;
            if(!$isAnyError) $isSuccessReq = $this->getModelUser()->updatePassword($db, $dataRequest['bodyData']);
            if($isSuccessReq) {
                $this->getModelUser()->deleteResetPassTokenByToken($db, $dataRequest['bodyData']);
            }

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        // Prepare pages
        public function authorizePageLogin() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);
        }

        public function authorizePageForgotPass() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);
        }      

        public function authorizePageRegister() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);  
        }

        public function authorizePageResetPassword() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();

            $isSessionActive = $this->isSessionActiveJwt($decodedJwt);
            //$isValidResetPassToken = $this->getControllerMain()->getControllerUser()->isValidResetPassToken();
            //$tokenToResetPass = $this->getControllerMain()->getControllerUser()->getDataResetPassFromToken();
            //var_dump($_GET['token']);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);  
        }
    }
?>