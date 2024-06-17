<?php



    interface I_ControllerStatistic {
        function renderPageDashboard($bearerJwt);
    }

    require_once "./Controller/ControllerUser.php";

    class ControllerStatistic implements I_ControllerStatistic {

        private $ContainerServices;
        private $ControllerUser;
        private $ControllerBase;
        private $db;
        
        public function __construct($ContainerServices) {
            $this->ContainerServices = $ContainerServices;

            //$this->ControllerUser = new ControllerUser;
            //$this->container = $container;
        }

        // ====== Controllers lazy loadings 
        /**
        * @return ControllerUser
        */
        private function getControllerUser() {
            if ($this->ControllerUser === null) $this->ControllerUser = $this->ContainerServices->get('ServiceUser');
            return $this->ControllerUser;
        }
        /**
        * @return ControllerBase
        */
        private function getControllerBase() {
            if ($this->ControllerBase === null) $this->ControllerBase = $this->ContainerServices->get('ControllerBase');
            return $this->ControllerBase;
        }
        
         // ====== DB connect 
        /**
        * @return Database
        */
        public function getDatabase() {
            if (!$this->db) $this->db = $this->ContainerServices->get('Database');
            return $this->db;
        }


        public function renderPageDashboard($tokenJwt) {
            $isUserConnected = $this->getControllerUser()->isUserConnected($tokenJwt);
            echo json_encode([
                'isUserConnected' => $isUserConnected,
                'TestVal' => 2,
            ]);
        }


    }


?>