<?php
    require_once './controller/ControllerMain.php';

    interface I_HandlerVerifyData {
        function getControllerMain();

    }

    class HandlerVerifyData implements I_HandlerVerifyData {

        private $ControllerMain;
        // Controller Main lazy loading
        /**
        * @return ControllerMain
        */
        // Model ControllerMain lazyloading
        public function getControllerMain() {
            if (!$this->ControllerMain) $this->ControllerMain = new ControllerMain();
            return $this->ControllerMain;
        }


        public function isValidTransactionAmount($trsAmount) {
            $regex = "/^\d+(,\d+)?$/";
            return preg_match($regex, $trsAmount) && strlen($trsAmount) <= 10;
        }

        public function isValidTransactionType($trsAmount) {
            
        }

        public function isValidTransactionCategory($trsAmount) {
            
        }

        public function isValidTransactionDate($trsDate) {
            $regex = "/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
            return preg_match($regex, $trsDate);
        }

        public function isValidTransactionNote($trsNote) {
            return strlen($trsNote) <= 30;
        }
    }
?>