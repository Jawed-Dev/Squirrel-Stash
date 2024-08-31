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
            $data = trim($data);
            $data = strip_tags($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
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
            if(empty($pass)) return false;
            if(!is_string($pass)) return false;
            $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[A-Za-z\d\W]{8,}$/";
            $isValidPattern = preg_match($regex, $pass) === 1;
            return $isValidPattern;
        }

        public function isValidMessage($message) {
            if(empty($message)) return false;
            if(!is_string($message)) return false;
            $isMaxLen = strlen($message) <= 1000;
            return $isMaxLen;
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
            $isValidPattern = preg_match($regex, $firstName) === 1;
            return $isValidPattern;
        }

        public function isValidHashSha256($hash) {
            if(empty($hash)) return false;
            if(!is_string($hash)) return false;
            $regex = '/^[0-9a-fA-F]{64}$/';
            $isValidPattern = preg_match($regex, $hash) === 1;
            return $isValidPattern;
        }
        
        public function isValidTokenJWt($token) {
            if(empty($token)) return false;
            if(!is_string($token)) return false;
            $isValidToken = $this->getControllerMain()->getHandlerJwt()->decodeJwt($token);
            return $isValidToken !== false;
        }

        public function isValidLastName($lastName) {
            if(empty($lastName)) return false;
            if(!is_string($lastName)) return false;
            $regex = "/^[A-Za-zàâçéèêëîïôûùüÿñæœ' -]{2,70}$/";
            $isValidPattern = preg_match($regex, $lastName) === 1;
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

        public function isValidBool($bool) {
            return is_bool($bool);
        }

        public function isInt($int) {
            if(!isset($int)) return false;
            if(!is_int($int)) return false;
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
            $maxVal = $trsAmount < 1000000;
            $isValidPattern = preg_match($regex, $trsAmount) === 1;
            $isInt = is_int($trsAmount);
            return $isValidPattern && $maxVal && $isInt;
        }

        public function isValidTransactionAmount($trsAmount) {
            if(empty($trsAmount)) return false;
            $maxVal = $trsAmount < 1000000;
            $regex = "/^\d+(\.\d{1,2})?$/";
            $isValidPattern = preg_match($regex, $trsAmount) === 1;
            $isFloat = is_float($trsAmount);
            $isInt = is_int($trsAmount);
            return $isValidPattern && $maxVal && ($isInt || $isFloat);
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

        public function isValidTransactionCategorySearch($trsCategory) {
            if(empty($trsCategory)) return false;
            if(!is_string($trsCategory)) return false;
            return true;
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
            $isValidPattern = preg_match($regex, $trsDate) === 1;
            return $isValidPattern;
        }

        public function isValidTransactionNote($trsNote) {
            if(!isset($trsNote)) return false;
            if(!is_string($trsNote)) return false;

            $maxLen = strlen($trsNote) <= 30;
            return $maxLen;
        }      

        public function isValidDateProfil($date) {
            if(!empty($date)) {
                if(!is_string($date)) return false;
                $regex = "/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
                $isValidPattern = preg_match($regex, $date) === 1;
                return $isValidPattern;
            }
            return true;
        }

        public function isValidGenderProfil($gender) {
            if(!empty($gender)){
                if(!is_string($gender)) return false;
                $isCorrectValue = $gender ==='Homme' || $gender ==='Femme' || $gender ==='Non défini';
                return $isCorrectValue;
            }
            return true;
        }

        public function isValidRoleLevel($roleLevel) {
            if(empty($roleLevel)) return false;
            if(!is_int($roleLevel)) return false;

            $isCorrectValue = $roleLevel !== 1;
            return $isCorrectValue;
        }
    }
?>