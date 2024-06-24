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
        // Statistic
            // get
            function fetchTrsMonthByDay();
            function fetchNLastTrsByMonth();
            function fetchThresholdByMonth();
            function fetchTotalTrsByMonth();
            function fetchBiggestTrsByMonth();
            // set
            function fetchSaveThreshold();
            function fetchInsertTransaction();
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

            $successReq = false;
            if($isTresholdExist) {
                $successReq = $this->getModelStatistic()->updateThresholdByMonth($db, $dataRequest);
            }
            else {
                $successReq = $this->getModelStatistic()->insertThresholdByMonth($db, $dataRequest);
            }
            //var_dump('tresholdExist',$isTresholdExist);
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        public function fetchInsertTransaction() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();

            $successReq = $this->getModelStatistic()->insertTransaction($db, $dataRequest);
            $this->getControllerMain()->sendJsonResponse(['isSuccessRequest' => $successReq]);
        }

        // get
        public function fetchTrsMonthByDay() {
            $db = $this->getControllerMain()->getDatabase();
            $dataRequest = $this->getControllerMain()->getHandlerJwt()->prepareDataForModel();
            $listTrsMonthByDay = $this->getModelStatistic()->getTrsMonthByDay($db, $dataRequest);
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