<?php 

    require_once './model/modelUser.php';
    require_once './view/viewUser.php';

    interface I_ControllerUser {
        // Main Controller lazy loading
        function getControllerMain();
        // Auth
        function isUserConnected($tokenJwt);
        function handleSuccessLogin($data);
        function getUserIdIntoJwt($decodedJwt);
        // Prepare pages
        function preparePageLogin($tokenJwt);
    }

    class ControllerUser implements I_ControllerUser {

        private $ContainerServices;
        private $ControllerMain;
        private $ModelUser;
        private $ViewUser;
        
        public function __construct($ContainerServices) {
            $this->ContainerServices = $ContainerServices;
        }

        // Controller Main lazy loading
        /**
        * @return ControllerMain
        */
        // Model ControllerMain lazyloading
        public function getControllerMain() {
            if (!$this->ControllerMain) $this->ControllerMain = $this->ContainerServices->getService('ControllerMain');
            return $this->ControllerMain;
        }
        // Model lazy loading
        public function getModelUser() {
            if (!$this->ModelUser) $this->ModelUser = new ModelUser;
            return $this->ModelUser;
        }
        // View lazy loading
        public function getViewMain() {
            if (!$this->ViewUser) $this->ViewUser = new ViewUser();
            return $this->ViewUser;
        }

        // Auth
        public function getUserIdIntoJwt($decodedJwt) {
            return $decodedJwt->userId;
        }

        public function isUserConnected($tokenJwt) {
            if($tokenJwt === null) return false;
            $decodedJwt = $this->getControllerMain()->decodeJwt($tokenJwt);
            if(!$decodedJwt) return false;

            $isValidTokenJwt = $this->getControllerMain()->isValidTokenJwt($decodedJwt);
            if(!$isValidTokenJwt) return false;

            $userId = $this->getUserIdIntoJwt($decodedJwt);
            $db = $this->getControllerMain()->getDatabase();
            
            $isUserExistById = $this->getModelUser()->isUserExistById($db, $userId);
            return $isUserExistById ;
        }
        public function handleSuccessLogin($data) {
            if(empty($data)) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);

            //var_dump($data);

            //$userConnected = $this->isUserConnected($data->token);
            //if($userConnected) return $this->getControllerMain()->sendJsonResponse(['connected' => true]);
            
            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getModelUser()->getUserIdByLogin($db, $data);
            if(!$userId) return $this->getControllerMain()->sendJsonResponse(['tokenJwt' => null]);

            $tokenJwt = null;
            $tokenJwt = $this->getControllerMain()->createTokenJwt($userId);
            $this->getControllerMain()->sendJsonResponse(['tokenJwt' => $tokenJwt]);
        }

        // Prepare pages
        public function preparePageLogin($tokenJwt) {
            $isUserConnected = $this->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ]; 
            $this->getViewMain()->renderPageLogin($dataPage);
        }

        
    }
?>