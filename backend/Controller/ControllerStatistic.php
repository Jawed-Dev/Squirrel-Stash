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
        // action data
        function fetchSaveThreshold();
        function fetchInsertTransaction();
        function fetchDeleteTransaction();
        function fetchUpdateTransaction();
        // Prepare page
        function authorizePageDashboard();
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
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $isTresholdExist = $this->getModelStatistic()->isThresholdExistByMonth($db, $dataRequest);
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifySaveThreshold($dataRequest['bodyData']);

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
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();

            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyInsertTransaction($dataRequest['bodyData']);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelStatistic()->insertTransaction($db, $dataRequest);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchDeleteTransaction() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();

            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyDeleteTransaction($dataRequest['bodyData']);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelStatistic()->deleteTransaction($db, $dataRequest);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchUpdateTransaction() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            //var_dump('test', $dataRequest);
            $isAnyError = $this->getControllerMain()->getHandlerError()->verifyUpdateTransaction($dataRequest['bodyData']);
            $successReq = false;
            if(!$isAnyError) $successReq = $this->getModelStatistic()->updateTransaction($db, $dataRequest);

            // log ici ?
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        // get
        public function fetchTrsMonthByDay() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $listTrsMonthByDay = $this->getModelStatistic()->getTrsMonthByDay($db, $dataRequest);
            //var_dump($dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $listTrsMonthByDay]);
            
        }

        public function fetchThresholdByMonth() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $amountThresholdByMonth = $this->getModelStatistic()->getThresholdByMonth($db, $dataRequest);
            //var_dump($db);
            $this->getControllerMain()->sendJsonResponse(['data' => $amountThresholdByMonth]);
        }

        public function fetchNLastTrsByMonth() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $listLastTrsByMonth = $this->getModelStatistic()->getNLastTrsByMonth($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $listLastTrsByMonth]);
        }

        public function fetchTotalTrsByMonth() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $totalTransactions = $this->getModelStatistic()->getTotalTrsByMonth($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $totalTransactions]);
        }

        public function fetchBiggestTrsByMonth() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $totalTransactions = $this->getModelStatistic()->getBiggestTrsByMonth($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['data' => $totalTransactions]);
        }

        // Prepare Pages
        public function authorizePageDashboard() {
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->getJwtFromHeader();
            $decodedJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($tokenJwt);
            $isSessionActive = $this->getControllerMain()->getControllerUser()->isSessionActiveJwt($decodedJwt);
            $dataPage = [
                'isSessionActive' => $isSessionActive,
            ];  
            $this->getViewStatistic()->renderPageDashboard($dataPage);
        }
    }
?>