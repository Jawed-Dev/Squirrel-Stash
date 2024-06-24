<?php 

    require_once './controller/ControllerMain.php';
    require_once './model/modelUser.php';
    require_once './view/viewUser.php';

    interface I_ControllerUser {
        // Main Controller
        function getControllerMain();
        // Model User
        function getModelUser();
        // View 
        function getViewUser();
        // Auth
        function isSessionActiveJwt($decodedJwt);
        function handleSuccessLogin();
        function getUserIdFromJwt($decodedJwt);
        // Prepare pages
        function authorizePageLogin();
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
            return $decodedJwt->userId;
        }
        public function isSessionActiveJwt($decodedJwt) {
            if(!$decodedJwt) return false;
            $isValidDecodedJwt = $this->getControllerMain()->getHandlerJwt()->isValidTokenJwt($decodedJwt);
            if(!$isValidDecodedJwt) return false;
            $userId = $this->getUserIdFromJwt($decodedJwt);
            $db = $this->getControllerMain()->getDatabase();
            $isUserExistById = $this->getModelUser()->isUserExistById($db, $userId);
            return $isUserExistById ;
        }

        public function handleSuccessLogin() {

            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);
            if(empty($data)) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);
            
            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getModelUser()->getUserIdByLogin($db, $data);
            if(!$userId) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);

            $tokenJwt = null;
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->createTokenJwt($userId);
            $this->getControllerMain()->sendJsonResponse(['tokenJwt' => $tokenJwt]);
        }

        // Prepare pages
        public function authorizePageLogin() {
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($tokenJwt);
            $isSessionActive = $this->isSessionActiveJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);
        }

        
    }
?>