<?php
    

    interface I_ControllerBase {
        function renderPageIndex();
    }

    //require_once './Controller/ControllerUser.php';

    class ControllerBase implements I_ControllerBase {

        private $Container;
        private $ControllerUser;
        private $ControllerStatistic;
        
        public function __construct($Container) {
            //$this->ControllerUser = new ControllerUser;
            $this->Container = $Container;
        }

        public function getControllerUser() {
            if (!$this->ControllerUser) $this->ControllerUser = $this->Container->get('ServiceUser');
            return $this->ControllerUser;
        }

        public function renderPageIndex() {
            echo json_encode([
                'isUserLog' => $this->getControllerUser()->isUserConnected(),
                'TestVal' => 2,
            ]);
        }
    }

?>