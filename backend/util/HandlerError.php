<?php
    require_once './controller/ControllerMain.php';

    interface I_HandlerError {
        // Main Controller 
        function getControllerMain();
        // Verify
        function verifyMainDataRequired($data);
        function verifyInsertTransaction($data);
        function verifyDeleteTransaction($data);
        function verifyUpdateTransaction($data);
        function verifySaveThreshold($data);
        function verifygetTrsMonthByDay($data);
        function verifyGetThresholdByMonth($data);
        function verifyGetNLastTrsByMonth($data);
        function verifyGetTotalTrsByMonth($data);
        function verifyGetBiggestTrsByMonth($data);
    }

    class HandlerError implements I_HandlerError {
        private $ControllerMain; 
        private $stateErrors = [];

        private $typeError = [
            'email' => [
                'code' => 0,
                'message' => "Adresse email invalide"
            ],
            'trsAmount' => [
                'code' => 1,
                'message' => "Montant invalide"
            ],
            'trsDate' => [
                'code' => 2,
                'message' => "Date invalide"
            ],
            'trsNote' => [
                'code' => 3,
                'message' => "La note est trop longue"
            ],
            'trsCategory' => [
                'code' => 4,
                'message' => "Informations invalides"
            ],
            'trsType' => [
                'code' => 5,
                'message' => "Informations invalides"
            ],
            'trsId' => [
                'code' => 6,
                'message' => "Informations invalides"
            ],
            'thresholdAmount' => [
                'code' => 7,
                'message' => "Informations invalides"
            ],
            'userId' => [
                'code' => 8,
                'message' => "userId invalide"
            ],
            'month' => [
                'code' => 9,
                'message' => "userId invalide"
            ],
            'year' => [
                'code' => 10,
                'message' => "userId invalide"
            ],
            'password' => [
                'code' => 11,
                'message' => "userId invalide"
            ],
            'firstName' => [
                'code' => 12,
                'message' => "userId invalide"
            ],
            'lastName' => [
                'code' => 12,
                'message' => "userId invalide"
            ],
            'resetPassToken' => [
                'code' => 13,
                'message' => "userId invalide"
            ],
            
        ];

        // Main Controller 
        /**
        * @return ControllerMain
        */
        public function getControllerMain() {
            if ($this->ControllerMain === null) $this->ControllerMain = new ControllerMain();
            return $this->ControllerMain;
        }

        private function getStateErrors() {
            return $this->stateErrors;
        }

        public function clearErrors() {
            $this->stateErrors = [];
        }

        public function addError($errorData) {
            $this->stateErrors[] = $errorData;
        }

        public function validateFormat($validationMethod, $data = null, $nameTypeErr) {
            $HandlerValidFormat = $this->getControllerMain()->getHandlerValidFormat();

            if (!method_exists($HandlerValidFormat, $validationMethod)) {
                throw new Exception("Erreur de donnée. (type: 3)");
            }
            
            $isValid = $HandlerValidFormat->$validationMethod($data);
            if(!$isValid) $this->addError($this->typeError[$nameTypeErr]);
        }

        public function verifyMainDataRequired($requiredData = []) {
            $localStateErrors = false;
            foreach ($requiredData as $data) {
                if (empty($data)) {
                    $localStateErrors = true;
                    break;
                }
            }
            return $localStateErrors;
        }

        // transactions 

        // action
        public function verifyInsertTransaction($data) {
            $bodyData = $data['bodyData'];
            $this->clearErrors();
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidTransactionAmount', $bodyData['transactionAmount'] ?? null, 'trsAmount');
            $this->validateFormat('isValidTransactionDate', $bodyData['transactionDate'] ?? null, 'trsDate');
            $this->validateFormat('isValidTransactionNote', $bodyData['transactionNote'] ?? null, 'trsNote');
            $this->validateFormat('isValidTransactionCategory', 
            [ $bodyData['transactionCategory'] ?? null, $bodyData['transactionType'] ] ?? null, 'trsCategory');
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null, 'trsType');     
            return $this->getStateErrors();
        }

        public function verifyUpdateTransaction($data) {
            $bodyData = $data['bodyData'];
            $this->clearErrors();
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidTransactionId', $bodyData['transactionId'] ?? null, 'trsId');
            $this->validateFormat('isValidTransactionAmount', $bodyData['transactionAmount'] ?? null, 'trsAmount');
            $this->validateFormat('isValidTransactionDate', $bodyData['transactionDate'] ?? null, 'trsDate');
            $this->validateFormat('isValidTransactionNote', $bodyData['transactionNote'] ?? null, 'trsNote');
            $this->validateFormat('isValidTransactionCategory', 
            [$bodyData['transactionCategory'] ?? null, $bodyData['transactionType']] ?? null, 'trsCategory');
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null, 'trsType');    
            return $this->getStateErrors();
        }

        public function verifyDeleteTransaction($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidTransactionId', $bodyData['transactionId'], 'trsId');
            return $this->getStateErrors();
        }

        public function verifySaveThreshold($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data, 'userId');

            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null, 'month');
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null, 'year');
            $this->validateFormat('isValidThresholdAmount', $bodyData['thresholdAmount'] ?? null, 'thresholdAmount'); 

            return $this->getStateErrors();
        }

        // get
        public function verifyGetTrsMonthByDay($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null, 'month');
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null, 'year');
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null, 'trsType');   
            return $this->getStateErrors();
        }

        public function verifyGetThresholdByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null, 'month');
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null, 'year');
            return $this->getStateErrors();
        }

        public function verifyGetNLastTrsByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null, 'month');
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null, 'year');
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null, 'trsType');   
            return $this->getStateErrors();
        }

        public function verifyGetTotalTrsByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null, 'month');
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null, 'year');
            return $this->getStateErrors();
        }

        public function verifyGetBiggestTrsByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data, 'userId');
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null, 'month');
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null, 'year');
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null, 'trsType');   
            return $this->getStateErrors();
        }

        // User 

        public function verifyHandleSuccessLogin($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidMail', $bodyData['email'] ?? null, 'userId');
            $this->validateFormat('isValidPass', $bodyData['password'] ?? null, 'userId');
            return $this->getStateErrors();
        }

        public function verifyInsertUser($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidMail', $bodyData['email'] ?? null, 'email');
            $this->validateFormat('isValidPass', $bodyData['password'] ?? null, 'password');
            $this->validateFormat('isValidFirstName', $bodyData['firstName'] ?? null, 'firstName');
            $this->validateFormat('isValidLastName', $bodyData['lastName'] ?? null, 'lastName');
            return $this->getStateErrors();
        }

        public function verifyIsValidResetPassToken($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidHashSha256', $bodyData['resetPassToken'] ?? null, 'resetPassToken');
            return $this->getStateErrors();
        }

        public function verifySendResetPassToken($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            //$this->validateFormat('isValidHashSha256', $bodyData['resetPassToken'] ?? null, 'resetPassToken');
            $this->validateFormat('isValidMail', $bodyData['email'] ?? null, 'resetPassToken');
            return $this->getStateErrors();
        }

        public function verifyUpdatePasswordByToken($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            
            $this->validateFormat('isValidPass', $bodyData['password'] ?? null, 'password');
            $this->validateFormat('isValidHashSha256', $bodyData['resetPassToken'] ?? null, 'resetPassToken');
            return $this->getStateErrors();
        }
        

    }
?>