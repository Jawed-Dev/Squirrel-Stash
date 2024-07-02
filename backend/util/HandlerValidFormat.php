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

        public function sanitizeData(&$data) {
            $data = htmlspecialchars($data);
            $data = trim($data);
            $data = strip_tags($data);
            return $data;
        }

        public function listAvailableYears() {
            $yearArray = [];
            for ($year = 2020; $year <= date("Y"); $year++) {
                $yearArray[] = $year;
            }
            return $yearArray;
        }

        public function isValidPass($pass) {
            // if(empty($pass)) return false;
            // if(!is_string($pass)) return false;
            // $regex = "/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/";
            // $isValidPattern = preg_match($regex, $pass);
            // return $isValidPattern;
            return true;
        }

        public function isValidMail($email) {
            if(empty($email)) return false;
            if(!is_string($email)) return false;
            $isValidPattern = filter_var($email, FILTER_SANITIZE_EMAIL);
            $isMaxLen = strlen($email) <= 254;
            return $isValidPattern && $isMaxLen;
        }

        public function isValidFirstName($firstName) {
            if(empty($firstName)) return false;
            if(!is_string($firstName)) return false;
            $regex = "/^[A-Za-zàâçéèêëîïôûùüÿñæœ' -]{2,50}$/";
            $isValidPattern = preg_match($regex, $firstName);
            return $isValidPattern;
        }

        public function isValidHashSha256($hash) {
            if(empty($hash)) return false;
            if(!is_string($hash)) return false;
            $regex = '/^[0-9a-fA-F]{64}$/';
            $isValidPattern = preg_match($regex, $hash);
            return $isValidPattern;
        }

        public function isValidLastName($lastName) {
            if(empty($lastName)) return false;
            if(!is_string($lastName)) return false;
            $regex = "/^[A-Za-zàâçéèêëîïôûùüÿñæœ' -]{2,70}$/";
            $isValidPattern = preg_match($regex, $lastName);
            return $isValidPattern;
        }

        public function isValidUserId($data) {
            $userId = $data['userId'];
            $db = $data['dataBase'];

            if(empty($userId)) return false;
            if(!is_int($userId)) return false;
            $ModelUser = $this->getControllerMain()->getControllerUser()->getModelUser();
            $isUserExist = $ModelUser->isUserExistFromId($db, $userId);
            if(!$isUserExist) return false;
            return true;
        }

        public function isValidTransactionId($trsId) {
            if(empty($trsId)) return false;
            if(!is_int($trsId)) return false;
            return true;
        }

        public function isValidThresholdAmount($trsAmount) {
            if(empty($trsAmount)) return false;

            $regex = "/^\d+(,\d+)?$/";
            $maxLen = strlen($trsAmount) <= 10;
            $isValidPattern = preg_match($regex, $trsAmount);
            $isInt = is_int($trsAmount);
            return $isValidPattern && $maxLen && $isInt;
        }

        public function isValidTransactionAmount($trsAmount) {
            if(empty($trsAmount)) return false;
            //if(!is_string($trsAmount)) return false;

            $maxLen = strlen($trsAmount) <= 10;
            $regex = "/^\d+(,\d+)?$/";
            $isValidPattern = preg_match($regex, $trsAmount);
            $isInt  = is_int($trsAmount);
            return $isValidPattern && $maxLen && $isInt;
        }

        public function isValidTransactionType($trsType) {
            if(empty($trsType)) return false;
            if(!is_string($trsType)) return false;

            $isCorrectValue = $trsType ==='purchase' || $trsType ==='recurring';
            return $isCorrectValue;
        }

        public function isValidTransactionCategory($data) {
            $trsCategory = $data[0];
            $trsType = $data[1];
            
            if(empty($trsCategory)) return false;
            if(empty($trsType)) return false;
            if(!is_string($trsCategory)) return false;
            if(!is_string($trsType)) return false;

            if($trsType === 'purchase') {
                return in_array($trsCategory, $this->listPurchaseCategories());
            }
            else if($trsType === 'recurring') {
                return in_array($trsCategory, $this->listRecurringCategories());
            }
            return false;
        }

        public function isValidYear($year) {
            if(empty($year)) return false;

            $isInt = is_int($year);
            $isCorrectValue = in_array($year, $this->listAvailableYears());
            return $isCorrectValue && $isInt;
        }

        function isValidMonth($month) {
            if(empty($month)) return false;

            $isInt = is_int($month);
            $isCorrectMonth = $month >= 0 && $month <= 12;
            return $isInt && $isCorrectMonth;
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