<?php
    require_once './controller/ControllerMain.php';
    require_once "./controller/controllerUser.php";
    require_once "./view/viewStatistic.php";
    require_once './model/ModelStatistic.php';

    interface I_ControllerStatistic {
        // Main Controller 
        function getControllerMain();
        // Model 
        function getModelStatistic();
        // View 
        function getViewStatistic();
        // get data
        function fetchTrsMonthByDay();
        function fetchNLastTrsByMonth();
        function fetchThresholdByMonth();
        function fetchTotalTrsByMonth();
        function fetchBiggestTrsByMonth();
        function fetchDataTrsBySearch();
        // action data
        function fetchSaveThreshold();
        function fetchInsertTransaction();
        function fetchDeleteTransaction();
        function fetchUpdateTransaction();
        // Prepare page
        function authorizePageDashboard();
        function authorizePageTransactions();
    }

    
    class ControllerStatistic implements I_ControllerStatistic {
        private $ControllerMain;
        private $ViewStatistic;
        private $ModelStatistic;

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
        * @return ModelStatistic
        */
        public function getModelStatistic() {
            if (!$this->ModelStatistic) $this->ModelStatistic = new ModelStatistic();
            return $this->ModelStatistic;
        }

        // View 
        public function getViewStatistic() {
            if (!$this->ViewStatistic) $this->ViewStatistic = new ViewStatistic();
            return $this->ViewStatistic;
        }

        // Statistic

        // Set
        public function fetchSaveThreshold() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();

            $db = $dataRequest['dataBase'];
            $isTresholdExist = $this->getModelStatistic()->isThresholdExistByMonth($db, $dataRequest);
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifySaveThreshold($dataRequest);
            $successReq = false;
            if($isTresholdExist) {
                if(!$isAnyError) $successReq = $this->getModelStatistic()->updateThresholdByMonth($db, $dataRequest);
            }
            else {
                if(!$isAnyError) $successReq = $this->getModelStatistic()->insertThresholdByMonth($db, $dataRequest);
            }
            
            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchInsertTransaction() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();

            //var_dump($dataRequest['dataBase']);
            $db = $dataRequest['dataBase'];
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest);
            $successReq = false;
            //var_dump($isAnyError);
            if(!$isAnyError) $successReq = $this->getModelStatistic()->insertTransaction($db, $dataRequest);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchDeleteTransaction() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();

            $db = $dataRequest['dataBase'];
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyDeleteTransaction($dataRequest);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelStatistic()->deleteTransaction($db, $dataRequest);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchUpdateTransaction() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();

            $db = $dataRequest['dataBase'];
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyUpdateTransaction($dataRequest);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelStatistic()->updateTransaction($db, $dataRequest);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        // get
        public function fetchDataTrsBySearch() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();

            $db = $dataRequest['dataBase'];
            $isAnyError = false;//$this->getControllerMain()->getHandlerError()->verifyGetTrsMonthByDay($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelStatistic()->getDataTrsBySearch($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function fetchTrsMonthByDay() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();

            $db = $dataRequest['dataBase'];
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyGetTrsMonthByDay($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelStatistic()->getTrsMonthByDay($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function fetchThresholdByMonth() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];

            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyGetThresholdByMonth($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelStatistic()->getThresholdByMonth($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function fetchNLastTrsByMonth() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            if(!$dataRequest) ('Erreur de donnée'); 
            $db = $dataRequest['dataBase'];

            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyGetNLastTrsByMonth($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelStatistic()->getNLastTrsByMonth($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function fetchTotalTrsByMonth() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];

            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyGetTotalTrsByMonth($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelStatistic()->getTotalTrsByMonth($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        public function fetchBiggestTrsByMonth() {
            $dataRequest = $this->getControllerMain()->prepareAndValidateData();
            $db = $dataRequest['dataBase'];

            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyGetBiggestTrsByMonth($dataRequest);
            $data = null;
            if(!$isAnyError) $data = $this->getModelStatistic()->getBiggestTrsByMonth($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $data]);
        }

        // Prepare Pages
        public function authorizePageDashboard() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->getControllerMain()->getControllerUser()->isSessionActiveByJwt($decodedJwt);
            $this->getViewStatistic()->renderPageDashboard(['isSessionActive' => $isSessionActive]);
        }
        public function authorizePageTransactions() {
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $isSessionActive = $this->getControllerMain()->getControllerUser()->isSessionActiveByJwt($decodedJwt);
            $this->getViewStatistic()->renderPageDashboard(['isSessionActive' => $isSessionActive]);
        }
    }
?>