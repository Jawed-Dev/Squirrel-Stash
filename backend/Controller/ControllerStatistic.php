<?php

    require_once "./Controller/ControllerUser.php";
    require_once "./View/ViewStatistic.php";


    interface I_ControllerStatistic {
        function getControllerMain();
        function preparePageDashboard($tokenJwt);
    }

    
    class ControllerStatistic implements I_ControllerStatistic {

        private $ContainerServices;
        private $ControllerMain;
        private $ViewStatistic;

        public function __construct($ContainerServices) {
            $this->ContainerServices = $ContainerServices;
            $this->ViewStatistic = new ViewStatistic;
        }

        // Main Controller lazy loading
        /**
        * @return ControllerMain
        */
        public function getControllerMain() {
            if ($this->ControllerMain === null) $this->ControllerMain = $this->ContainerServices->get('ControllerMain');
            return $this->ControllerMain;
        }

        // Prepare Pages
        public function preparePageDashboard($tokenJwt) {
            $isUserConnected = $this->getControllerMain()->getControllerUser()->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ];  
            $this->ViewStatistic->renderPageDashboard($dataPage);
        }


    }


?>