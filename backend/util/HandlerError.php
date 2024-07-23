<?php
    require_once './controller/ControllerMain.php';

    interface I_HandlerError {
        // Main Controller 
        function getControllerMain();
        // Verify
        function verifyIfMainDataExists($data);
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

        public function validateFormat($validationMethod, $data = null) {

            if(is_string($data) && $data) $this->getControllerMain()->getHandlerValidFormat()->sanitizeData($data);

            $HandlerValidFormat = $this->getControllerMain()->getHandlerValidFormat();
            if (!method_exists($HandlerValidFormat, $validationMethod)) {
                throw new Exception("Erreur de donnée. (type: 3)");
            }
            $isValid = $HandlerValidFormat->$validationMethod($data);
            if(!$isValid) $this->addError($validationMethod);
        }

        public function verifyIfMainDataExists($requiredData = []) {
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
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidTransactionAmount', $bodyData['transactionAmount'] ?? null);
            $this->validateFormat('isValidTransactionDate', $bodyData['transactionDate'] ?? null);
            $this->validateFormat('isValidTransactionNote', $bodyData['transactionNote'] ?? null);
            $this->validateFormat('isValidTransactionCategory', 
            [ $bodyData['transactionCategory'] ?? null, $bodyData['transactionType'] ] ?? null);
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null);     
            return $this->getStateErrors();
        }

        public function verifyUpdateTransaction($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidTransactionId', $bodyData['transactionId'] ?? null);
            $this->validateFormat('isValidTransactionAmount', $bodyData['transactionAmount'] ?? null);
            $this->validateFormat('isValidTransactionDate', $bodyData['transactionDate'] ?? null);
            $this->validateFormat('isValidTransactionNote', $bodyData['transactionNote'] ?? null);
            $this->validateFormat('isValidTransactionCategory', 
            [$bodyData['transactionCategory'] ?? null, $bodyData['transactionType']] ?? null);
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null);    
            return $this->getStateErrors();
        }

        public function verifyDeleteTransaction($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidTransactionId', $bodyData['transactionId'] ?? null);
            return $this->getStateErrors();
        }

        public function verifySaveThreshold($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null);
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null);
            $this->validateFormat('isValidThresholdAmount', $bodyData['thresholdAmount'] ?? null); 
            return $this->getStateErrors();
        }

        // get
        public function verifyGetTrsMonthByDay($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null);
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null);
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null);   
            return $this->getStateErrors();
        }

        public function verifyGetThresholdByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null);
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyGetNLastTrsByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null);
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null);
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null);   
            return $this->getStateErrors();
        }

        public function verifyGetTotalTrsByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null);
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyGetBiggestTrsByMonth($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidUserId', $data);
            $this->validateFormat('isValidMonth', $bodyData['selectedMonth'] ?? null);
            $this->validateFormat('isValidYear', $bodyData['selectedYear'] ?? null);
            $this->validateFormat('isValidTransactionType', $bodyData['transactionType'] ?? null);   
            return $this->getStateErrors();
        }

        // User 
        public function verifyHandleSuccessLogin($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidMail', $bodyData['email'] ?? null);
            $this->validateFormat('isValidPass', $bodyData['password'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyInsertUser($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidMail', $bodyData['email'] ?? null);
            $this->validateFormat('isValidPass', $bodyData['password'] ?? null);
            $this->validateFormat('isValidFirstName', $bodyData['firstName'] ?? null);
            $this->validateFormat('isValidLastName', $bodyData['lastName'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyIsValidResetPassToken($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidHashSha256', $bodyData['resetPassToken'] ?? null);
            return $this->getStateErrors();
        }

        public function verifySendResetPassToken($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidMail', $bodyData['email'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyUpdateEmail($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidHashSha256', $bodyData['token'] ?? null);
            return $this->getStateErrors();
        }

        public function verifySendUpdateMail($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidMail', $bodyData['newEmail'] ?? null);
            return $this->getStateErrors();
        }

        public function verifySendEmailToSupport($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidFirstName', $bodyData['firstName'] ?? null);
            $this->validateFormat('isValidLastName', $bodyData['lastName'] ?? null);
            $this->validateFormat('isValidMail', $bodyData['emailSender'] ?? null);
            $this->validateFormat('isValidMessage', $bodyData['message'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyUpdatePasswordByToken($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidHashSha256', $bodyData['resetPassToken'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyUpdatePasswordByUserId($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidPass', $bodyData['newPass'] ?? null);
            $this->validateFormat('isValidPass', $bodyData['oldPass'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyUpdateUserProfil($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            $this->validateFormat('isValidFirstName', $bodyData['firstName'] ?? null);
            $this->validateFormat('isValidLastName', $bodyData['lastName'] ?? null);
            $this->validateFormat('isValidDateProfil', $bodyData['birthday'] ?? null);
            $this->validateFormat('isValidGenderProfil', $bodyData['gender'] ?? null);
            return $this->getStateErrors();
        }

        public function verifyGetDataTrsBySearch($data) {
            $this->clearErrors();
            $bodyData = $data['bodyData'];
            //var_dump($bodyData);

            if(!empty($bodyData['searchNote'])) $this->validateFormat('isValidTransactionNote', $bodyData['searchNote']);
            if(!empty($bodyData['searchAmountMin'])) $this->validateFormat('isValidTransactionAmount', $bodyData['searchAmountMin']);
            if(!empty($bodyData['searchAmountMax'])) $this->validateFormat('isValidTransactionAmount', $bodyData['searchAmountMax']);
            
            if(!empty($bodyData['searchDateRangeDateMin'])) $this->validateFormat('isValidTransactionDate', $bodyData['searchDateRangeDateMin']);
            if(!empty($bodyData['searchDateRangeDateMax'])) $this->validateFormat('isValidTransactionDate', $bodyData['searchDateRangeDateMax']);
            
            if(!empty($bodyData['searchCategory'])) $this->validateFormat('isValidTransactionCategoryEx', $bodyData['searchCategory']);
            $this->validateFormat('isValidBool', $bodyData['orderAsc'] ?? null);
            $this->validateFormat('isInt', $bodyData['currentOrderSelected'] ?? null);
            $this->validateFormat('isInt', $bodyData['currentPage'] ?? 1);
            
            return $this->getStateErrors();
        }
    }
?>