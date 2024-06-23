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
        function isUserConnected($tokenJwt);
        function handleSuccessLogin();
        function getUserIdByDecodedJwt($decodedJwt);
        // Prepare pages
        function preparePageLogin();
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
        public function getUserIdByDecodedJwt($decodedJwt) {
            return $decodedJwt->userId;
        }
        public function isUserConnected($tokenJwt) {
            if($tokenJwt === null) return false;
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($tokenJwt);
            if(!$decodedJwt) return false;

            $isValidDecodedJwt = $this->getControllerMain()->getHandlerJwt()->isValidDecodedJwt($decodedJwt);
            if(!$isValidDecodedJwt) return false;

            $userId = $this->getUserIdByDecodedJwt($decodedJwt);
            $db = $this->getControllerMain()->getDatabase();
            
            $isUserExistById = $this->getModelUser()->isUserExistById($db, $userId);
            return $isUserExistById ;
        }
        public function handleSuccessLogin() {

            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);
            if(empty($data)) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);
            //var_dump($data);
            //$userConnected = $this->isUserConnected($data->token);
            //if($userConnected) return $this->getControllerMain()->sendJsonResponse(['connected' => true]);
            
            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getModelUser()->getUserIdByLogin($db, $data);
            if(!$userId) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);

            $tokenJwt = null;
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->createTokenJwt($userId);
            $this->getControllerMain()->sendJsonResponse(['tokenJwt' => $tokenJwt]);
        }

        // Prepare pages
        public function preparePageLogin() {
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->getBearerTokenJwt();
            $isUserConnected = $this->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ]; 
            $this->getViewUser()->renderPageLogin($dataPage);
        }

        
    }
?>