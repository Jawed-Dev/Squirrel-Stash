<?php

    interface I_ControllerStatistic {
        function renderPageDashboard();
    }

    require_once "./Controller/ControllerUser.php";

    class ControllerStatistic implements I_ControllerStatistic {

        private $container;
        private $ControllerUser;
        private $ControllerBase;
        
        public function __construct() {
            //$this->ControllerUser = new ControllerUser;
            //$this->container = $container;
        }

        private function getControllerUser() {
            if ($this->ControllerUser === null) $this->ControllerUser = $this->container->get('ControllerUser');
            return $this->ControllerUser;
        }

        public function renderPageDashboard() {
            echo json_encode([
                'isUserLog' => $this->ControllerUser->isUserConnected(),
                'TestVal' => 2,
            ]);
        }


    }


?>