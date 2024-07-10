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
        function fetchInsertUser();
        function isSessionActiveByJwt($decodedJwt);
        function handleSuccessLogin();
        function getUserIdFromJwt($decodedJwt);
        function getStateSession();
        function getUserFirstName();
        function FetchSendResetPassToken();
        function fetchUpdatePassword();
        function fetchIsValidResetPassToken();
        function getDataUserProfil();
        function updateDataUserProfil();
        
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

        private function getUrlToResetPass($tokenResetPass) {
            return FRONT_BASE_URL."/reinitialiser-mot-de-passe?token=".$tokenResetPass;
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
            $dataRequest = $this->getControllerMain()->prepareAndValidateData(false);
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
            $dataRequest = $this->getControllerMain()->prepareAndValidateData(false);
            $db = $dataRequest['dataBase'];
            
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyInsertUser($dataRequest);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelUser()->insertUser($db, $dataRequest['bodyData']);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchIsValidResetPassToken() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData(false);
            $db = $dataRequest['dataBase'];
            
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyIsValidResetPassToken($dataRequest);
            if($isAnyError) $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            $isSuccessReq = $this->getModelUser()->isValidResetPassToken($db, $dataRequest['bodyData']);
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function FetchSendResetPassToken() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData(false);
            $db = $dataRequest['dataBase'];
            
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifySendResetPassToken($dataRequest);
            $isSuccessReq = false;
            if($isAnyError) {
                $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
                return;
            }

            $dataBody = $dataRequest['bodyData'];
            $isLastTokenDeleted = $this->getModelUser()->deleteResetPassTokenByEmail($db, $dataBody);

            $uniqueToken = $this->getControllerMain()->getModelMain()->createRandomHashSha256();
            $uniqueTokenResetPass = $this->getModelUser()->insertUniqueTokenResetPass($db, $uniqueToken);
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
            // attention quand l'utilisateur sera connecté il faudra changer le code, si j'utilsie la mm fonction
            // pour que l'utiliseur change son mdp

            // ou tout simplement check si l'utilisateur est co, et renvoyé le boolééen dans la fonction
            $dataRequest = $this->getControllerMain()->prepareAndValidateData(false);
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

        

        

        public function getDataUserProfil() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];

            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifyGetBiggestTrsByMonth($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelUser()->getDataUserProfil($db, $userId);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function updateDataUserProfil() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];
            //var_dump($dataRequest);

            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifyGetBiggestTrsByMonth($dataRequest);
            $isSuccessReq = false;
            if(!$isAnyError) $isSuccessReq = $this->getModelUser()->updateDataUserProfil($db, $dataRequest);
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