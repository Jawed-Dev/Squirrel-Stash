<?php 

    require_once './Model/ModelUser.php';
    require_once './View/ViewUser.php';

    interface I_ControllerUser {
        // Auth
        function isUserConnected($tokenJwt);
        function handleSuccessLogin($data);
        // Prepare pages
        function preparePageLogin($tokenJwt);
    }

    class ControllerUser implements I_ControllerUser {

        private $ContainerServices;
        private $ControllerMain;
        private $ModelUser;
        private $db;
        private $ViewUser;
        
        public function __construct($ContainerServices) {
            $this->ContainerServices = $ContainerServices;
            $this->ModelUser = new ModelUser;
            $this->db = $this->getControllerMain()->getDatabase();
            $this->ViewUser = new ViewUser;
        }

        // Controller Main lazy loading
        /**
        * @return ControllerMain
        */
        public function getControllerMain() {
            if (!$this->ControllerMain) $this->ControllerMain = $this->ContainerServices->get('ControllerMain');
            return $this->ControllerMain;
        }

        // Auth
        public function isUserConnected($tokenJwt) {
            if($tokenJwt === null) return false;
            $decodedJwt = $this->getControllerMain()->decodeJwt($tokenJwt);
            if(!$decodedJwt) return false;

            $isValidTokenJwt = $this->getControllerMain()->isValidTokenJwt($decodedJwt);
            if(!$isValidTokenJwt) return false;

            $userId = $this->getControllerMain()->getUserIdIntoJwt($decodedJwt);
            $isUserExistById = $this->ModelUser->isUserExistById($this->db, $userId);
            return $isUserExistById ;
        }
        public function handleSuccessLogin($data) {
            $userId = $this->ModelUser->getUserIdByLogin($this->db, $data);
            $tokenJwt = null;

            if($userId) $tokenJwt = $this->getControllerMain()->createTokenJwt($userId);
            echo json_encode([
                'tokenJwt' => $tokenJwt
            ]);
        }

        // Prepare pages
        public function preparePageLogin($tokenJwt) {
            $isUserConnected = $this->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ]; 
            $this->ViewUser->renderPageLogin($dataPage);
        }
    }
?>