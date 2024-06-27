<?php
    require_once './controller/ControllerMain.php';

    interface I_HandlerValidFormat {
        function getControllerMain();

    }

    class HandlerValidFormat implements I_HandlerValidFormat {

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

        public function listPurchaseCategories() {
            return [
                'Alimentation',
                'Vestimentaire',
                'Famille',
                'Restaurant',
                'Loisir',
                'Santé',
                'Transport',
                'Cadeau',
                'Autre',
            ];
        }

        public function listRecurringCategories() {
            return [
                'Facture',
                'Loyer',
                'Assurance',
                'Crédit',
                'Abonnement',
                'Autre'
            ];
        }

        public function listAvailableYears() {
            $yearArray = [];
            for ($year = 2020; $year <= date("Y"); $year++) {
                $yearArray[] = $year;
            }
            return $yearArray;
        }

        public function isValidMail($mail) {
            if(empty($mail)) return false;
            if(!is_string($mail)) return false;
            $regex = "/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/";
            $isValidPattern = preg_match($regex, $mail);
            $isMaxLen = strlen($mail) <= 254;
            return $isValidPattern && $isMaxLen;
        }

        public function isValidTransactionId($trsId) {
            if(empty($trsId)) return false;
            $isInt = is_int($trsId);
            return $isInt;
        }

        public function isValidThresholdAmount($trsAmount) {
            if(empty($trsAmount)) return false;

            $regex = "/^\d+(,\d+)?$/";
            $maxLen = strlen($trsAmount) <= 10;
            $isValidPattern = preg_match($regex, $trsAmount);
            $isNumeric = is_numeric($trsAmount);
            return $isValidPattern && $maxLen && $isNumeric;
        }

        public function isValidTransactionAmount($trsAmount) {
            if(empty($trsAmount)) return false;
            //if(!is_string($trsAmount)) return false;

            $regex = "/^\d+(,\d+)?$/";
            $maxLen = strlen($trsAmount) <= 10;
            $isValidPattern = preg_match($regex, $trsAmount);
            $isNumeric  = is_numeric($trsAmount);
            return $isValidPattern && $maxLen && $isNumeric;
        }

        public function isValidTransactionType($trsType) {
            if(empty($trsType)) return false;
            if(!is_string($trsType)) return false;

            $isCorrectValue = $trsType ==='purchase' || $trsType ==='recurring';
            return $isCorrectValue;
        }

        public function isValidTransactionCategory($trsCategory, $trsType) {
            if(empty($trsCategory)) return false;
            if(!is_string($trsCategory)) return false;

            if($trsType === 'purchase') {
                return in_array($trsCategory, $this->listPurchaseCategories());
            }
            else if($trsType === 'recurring') {
                return in_array($trsCategory, $this->listRecurringCategories());
            }
            return false;
        }

        // year is stricly in int, it was help for for the front
        public function isValidYear($year) {
            if(empty($year)) return false;

            $isNumeric = is_numeric($year);
            $isCorrectValue = in_array($year, $this->listAvailableYears());
            return $isCorrectValue && $isNumeric;
        }

        function isValidMonth($month) {
            if(empty($month)) return false;

            $isNumeric = is_numeric($month);
            $isCorrectMonth = $month >= 0 && $month <= 12;
            return $isNumeric && $isCorrectMonth;
        }

        public function isValidTransactionDate($trsDate) {
            if(empty($trsDate)) return false;
            if(!is_string($trsDate)) return false;

            $regex = "/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
            $isValidPattern = preg_match($regex, $trsDate);
            return $isValidPattern;
        }

        public function isValidTransactionNote($trsNote) {
            if(!isset($trsNote)) return false;
            if(!is_string($trsNote)) return false;

            $maxLen = strlen($trsNote) <= 30;
            return $maxLen;
        }      
    }
?>