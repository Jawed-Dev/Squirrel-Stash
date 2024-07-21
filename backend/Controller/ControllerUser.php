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

        // get
        function getDataUserProfil();
        function isSessionActive($decodedJwt);
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
        function disconnectUser();
        function updateAccessToken();
        
        // Prepare pages
        function authorizePage();
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

        public function disconnectUser() {
            $isSuccessRequest = setcookie('refreshToken', '', [
                'expires' => time() - 1,
                'secure' => true,         
                'httponly' => true,         
                'samesite' => 'Strict'  
            ]);
            $_COOKIE['refreshToken'] = '';
            $this->getControllerMain()->sendJsonResponse(['successRequest' => $isSuccessRequest]);
        }

        public function getStateSession() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActive($decodedJwt);
            $this->getControllerMain()->sendJsonResponse(['isSessionActive' => $isSessionActive]);
        }

        public function isSessionActive($decodedJwt) {
            if(!$decodedJwt) return false;
            $isValidDecodedJwt = $this->getControllerMain()->getHandlerJwt()->isValidTokenJwt($decodedJwt);
            $tokenRefresh = null;

            if(!$isValidDecodedJwt) {
                $tokenRefresh = $this->getControllerMain()->getHandlerJwt()->getNewAccessToken();
                if(!$tokenRefresh) return false;

                $decodedTokenRefresh = $this->getControllerMain()->getHandlerJwt()->decodeJwt($tokenRefresh);
                $decodedJwt = $decodedTokenRefresh;
            }

            $userId = $this->getUserIdFromJwt($decodedJwt);
            $db = $this->getControllerMain()->getDatabase();
            $isUserExistById = $this->getModelUser()->isUserExistFromId($db, $userId);
            return $isUserExistById;
        }

        public function handleSuccessLogin() {
            $paramsValidation = [
                'requireAuth' => false, 
                'functionValidData' => 'verifyHandleSuccessLogin'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $userId = $this->getModelUser()->getUserIdIfValidLogin($db, $dataRequest['bodyData']);
            if(!$userId) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);
            
            $choiceStayConnected = $dataRequest['bodyData']['stayConnected'];
            $this->getControllerMain()->createCookieStayConnected($choiceStayConnected);

            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->createAccessTokenJwt($userId);
            $tokenRefreshJwt = $this->getControllerMain()->getHandlerJwt()->createRefreshTokenJwt($userId);
            $this->getControllerMain()->createCookieByRefreshToken($tokenRefreshJwt);
            $this->getControllerMain()->sendJsonResponse(['tokenJwt' => $tokenJwt]);
        }

        public function getUserFirstName() {
            $paramsValidation = ['requireBodyData' => false];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $userId = $dataRequest['userId'];
            $db = $dataRequest['dataBase'];
            $firstName = $this->getModelUser()->getUserFirstName($db ,$userId);
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['data' => $firstName]);
        }

        public function fetchInsertUser() {
            $paramsValidation = [
                'requireAuth' => false,
                'functionValidData' => 'verifyInsertUser'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelUser()->insertUser($db, $dataRequest['bodyData']);
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function updateAccessToken() {
            $refreshToken = getControllerMain()->getHandlerJwt()->getNewAccessToken();
            getControllerMain()->sendJsonResponse(['refreshToken' => $refreshToken]);
        }

        public function fetchIsValidResetPassToken() {
            $paramsValidation = [
                'requireAuth' => false,
                'functionValidData' => 'verifyIsValidResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->isValidResetPassToken($db, $dataRequest['bodyData']);
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function FetchSendResetPassToken() {
            $paramsValidation = [
                'requireAuth' => false,
                'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            
            $dataBody = $dataRequest['bodyData'];
            $isLastTokenDeleted = $this->getModelUser()->deleteResetPassToken($db, $dataBody);

            $uniqueToken = $this->getModelUser()->insertUniqueTokenResetPass($db);
            if(empty($uniqueToken)) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);
            $dataRequest['bodyData']['resetPassToken'] = $uniqueToken;

            $isSuccessRequestDb = $this->getModelUser()->insertResetPassToken($db, $dataRequest);
            if(!$isSuccessRequestDb) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            $requestUrl = $this->getUrlToResetPass($uniqueToken); 
            $paramsEmail = [
                'email' => $dataBody['email'],
                'urlToResetPass' => $requestUrl,
            ];
            //var_dump($params);
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailResetPass($paramsEmail);
            $isSuccessReq = $isSuccessRequestDb && $isSuccessReqEmail;
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function sendUpdateMail() {
            $paramsValidation = [
                'requireAuth' => false,
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];
            $currentEmail = $this->getModelUser()->getCurrentEmail($db, $userId);
            
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
            $isSuccessRequestDb = $this->getModelUser()->insertUpdateMailToken($db, $dataRequest);            
            if(!$isSuccessRequestDb) return $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => false]);

            // email config
            $requestUrl = $this->getUrlToUpdateMail($uniqueToken);
            $paramsEmail = [
                'email' => $receiverEmail,
                'urlToResetPass' => $requestUrl,
            ];
            //var_dump($params);
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailUpdateEmail($paramsEmail);
            $isSuccessReq = $isSuccessRequestDb && $isSuccessReqEmail;
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function sendEmailToSupport() {
            $paramsValidation = [
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $dataBody = $dataRequest['bodyData'];
            
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
            $paramsValidation = [
                'requireAuth' => false,
                //'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];

            $isSuccessReq = $this->getModelUser()->updatePasswordByToken($db, $dataRequest['bodyData']);
            if($isSuccessReq) {
                $this->getModelUser()->deleteResetPassTokenByToken($db, $dataRequest['bodyData']);
            }
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function updatePasswordByUserId() {
            $paramsValidation = [
                //'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->updatePasswordByUserId($db, $dataRequest);
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        public function updateEmail() {
            $paramsValidation = [
                //'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->updateEmail($db, $dataRequest);
            if($isSuccessReq) $this->getModelUser()->deleteUpdateEmailToken($db, $dataRequest);
    
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }        

        public function getDataUserProfil() {
            $paramsValidation = [
                //'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];
            $data = $this->getModelUser()->getDataUserProfil($db, $userId);
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function getUserEmail() {
            $paramsValidation = [
                //'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];
            $data = $this->getModelUser()->getCurrentEmail($db, $userId);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }
        

        public function updateUserProfil() {
            $paramsValidation = [
                //'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->updateUserProfil($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $isSuccessReq]);
        }

        // Prepare pages
        public function authorizePage() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActive($decodedJwt);
            $dataPage = ['isSessionActive' => $isSessionActive]; 
            $this->getViewUser()->renderJson($dataPage);
        }
    }
?>