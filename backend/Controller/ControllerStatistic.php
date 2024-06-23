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
        function fetchTrsMonthByDay();
        function fetchTransactionsDay($userId);
        function fetchNLastTrsByMonth();
        function fetchThresholdByMonth();
        function fetchTotalTrsByMonth();
        function fetchBiggestTrsByMonth();
        // Prepare page
        function preparePageDashboard();
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
        public function fetchTrsMonthByDay() {
            $codedTokenJwt = $this->getControllerMain()->getHandlerJwt()->getBearerTokenJwt();
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($codedTokenJwt);

            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);
            
            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getControllerMain()->getControllerUser()->getUserIdByDecodedJwt($tokenJwt);

            $dataModel = [
                'bodyData' => $data,
                'userId' => $userId
            ];
            $listTrsMonthByDay = $this->getModelStatistic()->getTrsMonthByDay($db, $dataModel);
            $this->getControllerMain()->sendJsonResponse(['data' => $listTrsMonthByDay]);
        }

        public function fetchThresholdByMonth() {
            $codedTokenJwt = $this->getControllerMain()->getHandlerJwt()->getBearerTokenJwt();
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($codedTokenJwt);
            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);
            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getControllerMain()->getControllerUser()->getUserIdByDecodedJwt($tokenJwt);
            $dataModel = [
                'bodyData' => $data,
                'userId' => $userId
            ];
            
            $amountThresholdByMonth = $this->getModelStatistic()->getThresholdByMonth($db, $dataModel);
            //var_dump($db);
            $this->getControllerMain()->sendJsonResponse(['data' => $amountThresholdByMonth]);
        }

        public function fetchNLastTrsByMonth() {
            $codedTokenJwt = $this->getControllerMain()->getHandlerJwt()->getBearerTokenJwt();
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($codedTokenJwt);

            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);

            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getControllerMain()->getControllerUser()->getUserIdByDecodedJwt($tokenJwt);
            $dataModel = [
                'bodyData' => $data,
                'userId' => $userId
            ];

            $listLastTrsByMonth = $this->getModelStatistic()->getNLastTrsByMonth($db, $dataModel);
            $this->getControllerMain()->sendJsonResponse(['data' => $listLastTrsByMonth]);
        }

        public function fetchTotalTrsByMonth() {
            $codedTokenJwt = $this->getControllerMain()->getHandlerJwt()->getBearerTokenJwt();
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($codedTokenJwt);

            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);

            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getControllerMain()->getControllerUser()->getUserIdByDecodedJwt($tokenJwt);
            $dataModel = [
                'bodyData' => $data,
                'userId' => $userId
            ];

            $totalTransactions = $this->getModelStatistic()->getTotalTrsByMonth($db, $dataModel);
            $this->getControllerMain()->sendJsonResponse(['data' => $totalTransactions]);
        }

        public function fetchBiggestTrsByMonth() {
            $codedTokenJwt = $this->getControllerMain()->getHandlerJwt()->getBearerTokenJwt();
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->decodeJwt($codedTokenJwt);

            $dataJson = $this->getControllerMain()->getRequestBodyJson();
            $data = json_decode($dataJson, true);

            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getControllerMain()->getControllerUser()->getUserIdByDecodedJwt($tokenJwt);
            $dataModel = [
                'bodyData' => $data,
                'userId' => $userId
            ];

            $totalTransactions = $this->getModelStatistic()->getBiggestTrsByMonth($db, $dataModel);
            $this->getControllerMain()->sendJsonResponse(['data' => $totalTransactions]);
        }

        public function fetchTransactionsDay($userId) {
            $db = $this->getControllerMain()->getDatabase();
        }

        

        // Prepare Pages
        public function preparePageDashboard() {
            $tokenJwt = $this->getControllerMain()->getHandlerJwt()->getBearerTokenJwt();
            $isUserConnected = $this->getControllerMain()->getControllerUser()->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ];  
            $this->getViewStatistic()->renderPageDashboard($dataPage);
        }


    }


?>