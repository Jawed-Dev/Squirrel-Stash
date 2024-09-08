<?php
    require_once './controllers/ControllerMain.php';
    require_once "./views/ViewStatistic.php";
    require_once './models/ModelStatisticAction.php';

    interface I_ControllerStatisticAction {
        // Main Controller 
        function getControllerMain();
        // Model 
        function getModelStatisticAction();
        // View 
        function getViewStatistic();
    
        // action data
        function saveThreshold();
        function addTransaction();
        function deleteTransaction();
        function updateTransaction();
        // Prepare page
        function authorizePage();
    }

    
    class ControllerStatisticAction implements I_ControllerStatisticAction {
        private $ControllerMain;
        private $ViewStatistic;
        private $ModelStatisticAction;

        // Main Controller 
        /**
        * @return ControllerMain
        */
        public function getControllerMain() {
            if ($this->ControllerMain === null) $this->ControllerMain = new ControllerMain();
            return $this->ControllerMain;
        }

        // Model 
        /**
        * @return ModelStatisticAction
        */
        public function getModelStatisticAction() {
            if (!$this->ModelStatisticAction) $this->ModelStatisticAction = new ModelStatisticAction();
            return $this->ModelStatisticAction;
        }

        // View 
        public function getViewStatistic() {
            if (!$this->ViewStatistic) $this->ViewStatistic = new ViewStatistic();
            return $this->ViewStatistic;
        }

        // action data
        public function saveThreshold() {
            $paramsValidation = ['functionValidData' => 'verifySaveThreshold'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isTresholdExist = $this->getControllerMain()->getControllerStatisticGetData()->getModelStatisticDataMonth()->isThresholdExistByMonth($db, $dataRequest);
            if($isTresholdExist) $successReq = $this->getModelStatisticAction()->updateThresholdByMonth($db, $dataRequest);
            else $successReq = $this->getModelStatisticAction()->addThresholdByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        public function addTransaction() {
            $paramsValidation = ['functionValidData' => 'verifyAddTransaction'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelStatisticAction()->addTransaction($db, $dataRequest);
            
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        public function deleteTransaction() {
            $paramsValidation = ['functionValidData' => 'verifyDeleteTransaction'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelStatisticAction()->deleteTransaction($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        public function updateTransaction() {
            $paramsValidation = ['functionValidData' => 'verifyUpdateTransaction'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation );

            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelStatisticAction()->updateTransaction($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        // Prepare Pages
        public function authorizePage() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->getControllerMain()->getControllerUser()->isSessionActive($decodedJwt);
            // log ici ?
            $this->getViewStatistic()->renderJson(['isSessionActive' => $isSessionActive]);
        }
    }
?>