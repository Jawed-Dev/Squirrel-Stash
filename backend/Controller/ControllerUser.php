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

        // User

        // get
        function getDataUserProfil();
        function isSessionActiveByJwt($decodedJwt);
        function fetchInsertUser();
        function getUserIdFromJwt($decodedJwt);
        function getStateSession();
        function getUserFirstName();
        function getUserEmail();

        // action
        function handleSuccessLogin();
        function FetchSendResetPassToken();
        function updatePasswordByToken();
        function updatePasswordByUserId();
        function fetchIsValidResetPassToken();
        function updateUserProfil();
        function sendUpdateMail();
        function updateEmail();
        function sendEmailToSupport();
        
        // Prepare pages
        function authorizePageLogin();
        function authorizePageForgotPass();
        function authorizePageRegister();
        function authorizePageResetPassword();
        function authorizePageUser();
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

    
        public function getUserIdFromJwt($decodedJwt) {
            return !empty($decodedJwt->userId) ? $decodedJwt->userId : null;
        }

        private function getUrlToResetPass($token) {
            return FRONT_BASE_URL."/reinitialiser-mot-de-passe?token=".$token;
        }

        private function getUrlToUpdateMail($token) {
            return FRONT_BASE_URL."/reinitialiser-email?token=".$token;
        }

        public function getStateSession() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveByJwt($decodedJwt);
            $this->getControllerMain()->sendJsonResponse(['isSessionActive' => $isSessionActive]);
        }

        public function isSessionActiveByJwt($decodedJwt) {
            if(!$decodedJwt) return false;
            $isValidDecodedJwt = $this->getControllerMain()->getHandlerJwt()->isValidTokenJwt($decodedJwt);
            if(!$isValidDecodedJwt) return false;
            $userId = $this->getUserIdFromJwt($decodedJwt);
            $db = $this->getControllerMain()->getDatabase();
            $isUserExistFromId = $this->getModelUser()->isUserExistFromId($db, $userId);
            return $isUserExistFromId;
        }

        public function handleSuccessLogin() {
            $requiredAuthFalse = false;
            $dataRequest = $this->getControllerMain()->prepareAndValidateData($requiredAuthFalse);
            $db = $dataRequest['dataBase'];

            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyHandleSuccessLogin($dataRequest);
            if($isAnyError) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);

            $userId = $this->getModelUser()->getUserIdIfValidLogin($db, $dataRequest['bodyData']);
            if(!$userId) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->createTokenJwt($userId);
            $this->getControllerMain()->sendJsonResponse(['tokenJwt' => $tokenJwt]);
        }

        public function getUserFirstName() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $userId = $this->getUserIdFromJwt($decodedJwt);
            $db = $this->getControllerMain()->getDatabase();
            $data = $this->getModelUser()->getUserFirstName($db ,$userId);
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function fetchInsertUser() {
            $requiredAuthFalse = false;
            $dataRequest = $this->getControllerMain()->prepareAndValidateData($requiredAuthFalse);
            $db = $dataRequest['dataBase'];
            
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyInsertUser($dataRequest);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelUser()->insertUser($db, $dataRequest['bodyData']);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchIsValidResetPassToken() {
            $requiredAuthFalse = false;
            $dataRequest = $this->getControllerMain()->prepareAndValidateData($requiredAuthFalse);
            $db = $dataRequest['dataBase'];
            
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyIsValidResetPassToken($dataRequest);
            if($isAnyError) $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            $isSuccessReq = $this->getModelUser()->isValidResetPassToken($db, $dataRequest['bodyData']);
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function FetchSendResetPassToken() {
            $requiredAuthFalse = false;
            $dataRequest = $this->getControllerMain()->prepareAndValidateData($requiredAuthFalse);
            $db = $dataRequest['dataBase'];
            
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifySendResetPassToken($dataRequest);
            $isSuccessReq = false;
            if($isAnyError) {
                $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
                return;
            }

            $dataBody = $dataRequest['bodyData'];
            $isLastTokenDeleted = $this->getModelUser()->deleteResetPassToken($db, $dataBody);

            $uniqueToken = $this->getModelUser()->insertUniqueTokenResetPass($db);
            if(empty($uniqueToken)) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
            $dataRequest['bodyData']['resetPassToken'] = $uniqueToken;

            $isSuccessReqDb = $this->getModelUser()->insertResetPassToken($db, $dataRequest);
            if(!$isSuccessReqDb) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            $requestUrl = $this->getUrlToResetPass($uniqueToken); 
            $paramsEmail = [
                'email' => $dataBody['email'],
                'urlToResetPass' => $requestUrl,
            ];
            //var_dump($params);
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailResetPass($paramsEmail);
            $isSuccessReq = $isSuccessReqDb && $isSuccessReqEmail;
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function sendUpdateMail() {

            // prepare data
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];
            $currentEmail = $this->getModelUser()->getCurrentEmail($db, $userId);
            
            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifySendResetPassToken($dataRequest);
            $isSuccessReq = false;
            if($isAnyError) {
                $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
                return;
            }

            $dataBody = $dataRequest['bodyData'];
            //delete last token
            $isLastTokenDeleted = $this->getModelUser()->deleteUpdateEmailToken($db, $dataRequest);

            // unique token
            $uniqueToken = $this->getModelUser()->insertUniqueTokenUpdateEmail($db);
            if(empty($uniqueToken)) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            $dataRequest['bodyData']['token'] = $uniqueToken;
            $dataRequest['bodyData']['currentEmail'] = $currentEmail;
            $receiverEmail = $dataBody['newEmail'];
            // insert token
            $isSuccessReqDb = $this->getModelUser()->insertUpdateMailToken($db, $dataRequest);            
            if(!$isSuccessReqDb) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            // email config
            $requestUrl = $this->getUrlToUpdateMail($uniqueToken);
            $paramsEmail = [
                'email' => $receiverEmail,
                'urlToResetPass' => $requestUrl,
            ];
            //var_dump($params);
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailUpdateEmail($paramsEmail);
            $isSuccessReq = $isSuccessReqDb && $isSuccessReqEmail;
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function sendEmailToSupport() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];
            $dataBody = $dataRequest['bodyData'];

            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifySendResetPassToken($dataRequest);
            $isSuccessReq = false;
            if($isAnyError) {
                $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
                return;
            }

            $paramsEmail = [
                'firstName' => $dataBody['firstName'],
                'lastName' => $dataBody['lastName'],
                'emailSender' => $dataBody['emailSender'],
                'message' => $dataBody['message'],
            ];
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailToSupport($paramsEmail);
            $isSuccessReq = $isSuccessReqEmail;

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function updatePasswordByToken() {
            $requiredAuthFalse = false;
            $dataRequest = $this->getControllerMain()->prepareAndValidateData($requiredAuthFalse);
            $db = $dataRequest['dataBase'];

            // j'ai commencé le verify
            $isAnyError = false; //$this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $isSuccessReq = false;
            if(!$isAnyError) $isSuccessReq = $this->getModelUser()->updatePasswordByToken($db, $dataRequest['bodyData']);
            if($isSuccessReq) {
                $this->getModelUser()->deleteResetPassTokenByToken($db, $dataRequest['bodyData']);
            }
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function updatePasswordByUserId() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];

            $isAnyError = false; //$this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $isSuccessReq = false;
            if(!$isAnyError) $isSuccessReq = $this->getModelUser()->updatePasswordByUserId($db, $dataRequest);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function updateEmail() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];

            $isAnyError = false; //$this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $isSuccessReq = false;
            if(!$isAnyError) $isSuccessReq = $this->getModelUser()->updateEmail($db, $dataRequest);
            if($isSuccessReq) {
                $this->getModelUser()->deleteUpdateEmailToken($db, $dataRequest);
            }

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }        

        public function getDataUserProfil() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];

            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifyGetBiggestTrsByMonth($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelUser()->getDataUserProfil($db, $userId);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function getUserEmail() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];

            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifyGetBiggestTrsByMonth($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelUser()->getCurrentEmail($db, $userId);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }
        

        public function updateUserProfil() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];
            //var_dump($dataRequest);

            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifyGetBiggestTrsByMonth($dataRequest);
            $isSuccessReq = false;
            if(!$isAnyError) $isSuccessReq = $this->getModelUser()->updateUserProfil($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        // Prepare pages
        public function authorizePageLogin() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveByJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);
        }

        public function authorizePageForgotPass() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveByJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);
        }      

        public function authorizePageRegister() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveByJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);  
        }

        public function authorizePageResetPassword() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveByJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);  
        }

        public function authorizePageUser() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActiveByJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);  
        }
    }
?>