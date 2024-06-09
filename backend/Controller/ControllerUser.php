<?php 

    interface I_ControllerUser {
        function isUserConnected();
        function renderPageLogin();
    }

    class ControllerUser {

        private $Container;
        
        public function __construct($Container) {
            $this->Container = $Container;
        }

        public function isUserConnected() {
            $isConnected = true;
            return $isConnected;
        }


        public function renderPageLogin() {
            echo json_encode([
                'isUserLog' => $this->isUserConnected(),
                'TestVal' => 2,
            ]);
        }


        
    }

?>