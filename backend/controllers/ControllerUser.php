<?php 

    require_once './controllers/ControllerMain.php';
    require_once './models/ModelUser.php';
    require_once './views/ViewUser.php';
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
        function addNewUser();
        function getUserIdFromJwt($decodedJwt);
        function getStateSession();
        function getUserFirstName();
        function getUserEmail();

        // action
        function handleSuccessLogin();
        function sendResetPassToken();
        function updatePasswordByToken();
        function updatePasswordByUserId();
        function isValidResetPassToken();
        function updateUserProfil();
        function sendUpdateMail();
        function updateEmail();
        function sendEmailToSupport();
        
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

        public function getStateSession() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->isSessionActive($decodedJwt);
            $this->getViewUser()->renderJson(['isSessionActive' => $isSessionActive]);
        }

        public function isSessionActive($decodedJwt) {
            if(!$decodedJwt) return false;
            $isValidDecodedJwt = $this->getControllerMain()->getHandlerJwt()->isValidTokenJwt($decodedJwt);
            if(!$isValidDecodedJwt) return false;
            
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
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $userId = $this->getModelUser()->getUserIdIfValidLogin($db, $dataRequest['bodyData']);
            if(!$userId) return $this->getViewUser()->renderJson(['tokenJwt' => null]);
            $choiceStayConnect = $dataRequest['bodyData']['stayConnected'];
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->createTokenJwt($userId, $choiceStayConnect);
            $this->getViewUser()->renderJson(['tokenJwt' => $tokenJwt]);
        }

        public function getUserFirstName() {
            $paramsValidation = ['requireBodyData' => false];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $userId = $dataRequest['userId'];
            $db = $dataRequest['dataBase'];
            $firstName = $this->getModelUser()->getUserFirstName($db ,$userId);
            // log ici ?
            $this->getViewUser()->renderJson(['data' => $firstName]);
        }

        public function addNewUser() {
            $paramsValidation = [
                'requireAuth' => false,
                'functionValidData' => 'verifyaddNewUser'
            ];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelUser()->insertUser($db, $dataRequest['bodyData']);
            // log ici ?
            $this->getViewUser()->renderJson(['isSuccessRequest' => $successReq]);
        }

        public function isValidResetPassToken() {
            $paramsValidation = [
                'requireAuth' => false,
                'functionValidData' => 'verifyIsValidResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->isValidResetPassToken($db, $dataRequest['bodyData']);
            // log ici ?
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
        }

        public function sendResetPassToken() {
            $paramsValidation = [
                'requireAuth' => false,
                'functionValidData' => 'verifySendResetPassToken'
            ];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            
            $dataBody = $dataRequest['bodyData'];
            $isLastTokenDeleted = $this->getModelUser()->deleteResetPassToken($db, $dataBody);

            $uniqueToken = $this->getModelUser()->insertUniqueTokenResetPass($db);
            if(empty($uniqueToken)) return $this->getViewUser()->renderJson(['isSuccessRequest' => false]);
            $dataRequest['bodyData']['resetPassToken'] = $uniqueToken;

            $isSuccessRequestDb = $this->getModelUser()->insertResetPassToken($db, $dataRequest);
            if(!$isSuccessRequestDb) return $this->getViewUser()->renderJson(['isSuccessRequest' => false]);

            $requestUrl = $this->getUrlToResetPass($uniqueToken); 
            $paramsEmail = [
                'email' => $dataBody['email'],
                'urlToResetPass' => $requestUrl,
            ];
            //var_dump($params);
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailResetPass($paramsEmail);
            $isSuccessReq = $isSuccessRequestDb && $isSuccessReqEmail;
            
            // log ici ?
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
        }

        public function sendUpdateMail() {
            $paramsValidation = [
                'functionValidData' => 'verifySendUpdateMail'
            ];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];
            $currentEmail = $this->getModelUser()->getCurrentEmail($db, $userId);
            
            $dataBody = $dataRequest['bodyData'];
            //delete last token
            $isLastTokenDeleted = $this->getModelUser()->deleteUpdateEmailToken($db, $dataRequest);
            // unique token
            $uniqueToken = $this->getModelUser()->insertUniqueTokenUpdateEmail($db);
            if(empty($uniqueToken)) return $this->getViewUser()->renderJson(['isSuccessRequest' => false]);

            $dataRequest['bodyData']['token'] = $uniqueToken;
            $dataRequest['bodyData']['currentEmail'] = $currentEmail;
            $receiverEmail = $dataBody['newEmail'];
            // insert token
            $isSuccessRequestDb = $this->getModelUser()->insertUpdateMailToken($db, $dataRequest);            
            if(!$isSuccessRequestDb) return $this->getViewUser()->renderJson(['isSuccessRequest' => false]);

            // email config
            $requestUrl = $this->getUrlToUpdateMail($uniqueToken);
            $paramsEmail = [
                'email' => $currentEmail,
                'urlToResetPass' => $requestUrl,
            ];
            //var_dump($params);
            $isSuccessReqEmail = $this->getControllerMain()->getEmailSender()->sendEmailUpdateEmail($paramsEmail);
            $isSuccessReq = $isSuccessRequestDb && $isSuccessReqEmail;
            
            // log ici ?
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
        }

        public function sendEmailToSupport() {
            $paramsValidation = [
                'requireDatabase' => false,
                'allowForAllAuth' => true,
                'functionValidData' => 'verifySendEmailToSupport'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);
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
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
        }

        // update password when the password is forgotten
        public function updatePasswordByToken() {
            $paramsValidation = [
                'requireAuth' => false,
                'functionValidData' => 'verifyUpdatePasswordByToken'
            ];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);
            $db = $dataRequest['dataBase'];

            $isSuccessReq = $this->getModelUser()->updatePasswordByToken($db, $dataRequest['bodyData']);
            if($isSuccessReq) {
                $this->getModelUser()->deleteResetPassTokenByToken($db, $dataRequest['bodyData']);
            }
            // log ici ?
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
        }

        // update password when user is connected
        public function updatePasswordByUserId() {
            $paramsValidation = ['functionValidData' => 'verifyUpdatePasswordByUserId']; 
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->updatePasswordByUserId($db, $dataRequest);
            // log ici ?
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
        }

        public function updateEmail() {
            $paramsValidation = ['functionValidData' => 'verifyUpdateEmail'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->updateEmail($db, $dataRequest);
            if($isSuccessReq) $this->getModelUser()->deleteUpdateEmailToken($db, $dataRequest);
    
            // log ici ?
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
        }        

        public function getDataUserProfil() {
            $paramsValidation = []; //
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];
            $data = $this->getModelUser()->getDataUserProfil($db, $userId);
            // log ici ?
            $this->getViewUser()->renderJson(['data' => $data]);
        }

        public function getUserEmail() {
            $paramsValidation = []; //
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $userId = $dataRequest['userId'];
            $data = $this->getModelUser()->getCurrentEmail($db, $userId);
            $this->getViewUser()->renderJson(['data' => $data]);
        }
        

        public function updateUserProfil() {
            $paramsValidation = ['functionValidData' => 'verifyUpdateUserProfil'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isSuccessReq = $this->getModelUser()->updateUserProfil($db, $dataRequest);
            $this->getViewUser()->renderJson(['isSuccessRequest' => $isSuccessReq]);
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