<?php 

    require_once './Model/ModelUser.php';

    interface I_ControllerUser {
        function isUserConnected($bearerJwt);
        function renderPageLogin($bearerJwt);
        function handleSuccessLogin($data);
        function getControllerBase();
        function getDatabase();
    }

    class ControllerUser implements I_ControllerUser {

        private $Container;
        private $ControllerBase;
        private $ModelUser;
        private $db;
        
        public function __construct($Container) {
            $this->Container = $Container;
            $this->ModelUser = new ModelUser;
        }

        // ====== Controllers lazy loadings 
        /**
        * @return ControllerBase
        */
        public function getControllerBase() {
            if (!$this->ControllerBase) $this->ControllerBase = $this->Container->get('ServiceBase');
            return $this->ControllerBase;
        }
         // ====== DB connect 
        /**
        * @return Database
        */
        public function getDatabase() {
            if (!$this->db) $this->db = $this->Container->get('Database');
            return $this->db;
        }

        // functions

        public function isUserConnected($bearerJwt) {

            //var_dump($bearerJwt);

            if($bearerJwt === null) return false;
            $decodedJwt = $this->getControllerBase()->decodeJwt($bearerJwt);
            
            //if(!$decodedJwt) return false;

            $isValidToken = $this->getControllerBase()->isValidToken($decodedJwt);
            //var_dump($isValidToken);

            if(!$isValidToken) return false;

            $userId = $this->getControllerBase()->getUserIdIntoToken($decodedJwt);

            $db = $this->getDatabase();
            $isUserExistById = $this->ModelUser->isUserExistById($db, $userId);

            return $isUserExistById ;
        }

        public function renderPageLogin($bearerJwt) {
            $isUserConnected = $this->isUserConnected($bearerJwt);
            echo json_encode([
                'isUserConnected' => $isUserConnected,
                'TestVal' => 2,
            ]);
        }

        public function handleSuccessLogin($data) {

            $db = $this->getDatabase();
            $userId = $this->ModelUser->getUserIdByLogin($db, $data);
            $tokenJwt = null;
            if($userId) $tokenJwt = $this->getControllerBase()->createJwtUser($userId);

            echo json_encode([
                'tokenJwt' => $tokenJwt
            ]);
        }
        
    }

?>