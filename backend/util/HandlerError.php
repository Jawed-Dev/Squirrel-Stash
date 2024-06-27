<?php
    require_once './controller/ControllerMain.php';

    interface I_HandlerError {
        // Main Controller 
        function getControllerMain();
        // Verify
        function verifyInsertTransaction($data);
        function verifyDeleteTransaction($data);
        function verifyUpdateTransaction($data);
        function verifySaveThreshold($data);
    }

    class HandlerError implements I_HandlerError {
        private $ControllerMain; 
        private $stateErrors = [];

        private $typeError = [
            'mail' => [
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
            ]
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

        public function verifyInsertTransaction($data) {
            $this->stateErrors = [];
            
            $isValidTransactionAmount = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionAmount($data['transactionAmount']);
            $isValidTransactionDate = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionDate($data['transactionDate']);
            $isValidTransactionNote = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionNote($data['transactionNote']);
            $isValidTransactionCategory = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionCategory($data['transactionCategory'], $data['transactionType']);
            $isValidTransactionType = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionType($data['transactionType']);

            if(!$isValidTransactionAmount) $this->stateErrors[] = $this->typeError['trsAmount'];
            if(!$isValidTransactionDate) $this->stateErrors[] = $this->typeError['trsDate'];
            if(!$isValidTransactionNote) $this->stateErrors[] = $this->typeError['trsNote'];
            if(!$isValidTransactionCategory) $this->stateErrors[] = $this->typeError['trsCategory'];
            if(!$isValidTransactionType) $this->stateErrors[] = $this->typeError['trsType'];
            
            return $this->getStateErrors();
        }

        public function verifyUpdateTransaction($data) {
            $this->stateErrors = [];
            $isValidTransactionId= $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionId($data['transactionId']);
            $isValidTransactionAmount = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionAmount($data['transactionAmount']);
            $isValidTransactionType = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionType($data['transactionType']);
            $isValidTransactionCategory = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionCategory($data['transactionCategory'], $data['transactionType']);
            $isValidTransactionDate = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionDate($data['transactionDate']);
            $isValidTransactionNote = $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionNote($data['transactionNote']);

            if(!$isValidTransactionId) $this->stateErrors[] = $this->typeError['trsId'];
            if(!$isValidTransactionAmount) $this->stateErrors[] = $this->typeError['trsAmount'];
            if(!$isValidTransactionType) $this->stateErrors[] = $this->typeError['trsType'];
            if(!$isValidTransactionCategory) $this->stateErrors[] = $this->typeError['trsCategory'];
            if(!$isValidTransactionDate) $this->stateErrors[] = $this->typeError['trsDate'];
            if(!$isValidTransactionNote) $this->stateErrors[] = $this->typeError['trsNote'];
            return $this->getStateErrors();
        }

        public function verifyDeleteTransaction($data) {
            $this->stateErrors = [];
            $isValidTransactionId= $this->getControllerMain()->getHandlerValidFormat()->isValidTransactionId($data['transactionId']);

            if(!$isValidTransactionId) $this->stateErrors[] = $this->typeError['trsId'];
            return $this->getStateErrors();
        }

        public function verifySaveThreshold($data) {
            $this->stateErrors = [];
            $isValidThresholdAmount= $this->getControllerMain()->getHandlerValidFormat()->isValidThresholdAmount($data['thresholdAmount']);

            if(!$isValidThresholdAmount) $this->stateErrors[] = $this->typeError['thresholdAmount'];
            return $this->getStateErrors();
        }
    }
?>