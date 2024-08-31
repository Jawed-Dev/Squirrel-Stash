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

        function getYearListTrsByMonth();
        function getTotalTrsByYear();
        function getBiggestTrsByYear();
        function getBiggestMonthByYear();
        function getYearListTrsByCategories();
        function getTopYearCategories();

        // action data
        function fetchSaveThreshold();
        function addTransaction();
        function fetchDeleteTransaction();
        function fetchUpdateTransaction();
        // Prepare page
        function authorizePage();
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

        // action data
        public function fetchSaveThreshold() {
            $paramsValidation = ['functionValidData' => 'verifySaveThreshold'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $isTresholdExist = $this->getModelStatistic()->isThresholdExistByMonth($db, $dataRequest);
            if($isTresholdExist) $successReq = $this->getModelStatistic()->updateThresholdByMonth($db, $dataRequest);
            else $successReq = $this->getModelStatistic()->insertThresholdByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        public function addTransaction() {
            $paramsValidation = ['functionValidData' => 'verifyAddTransaction'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelStatistic()->addTransaction($db, $dataRequest);
            
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        public function fetchDeleteTransaction() {
            $paramsValidation = ['functionValidData' => 'verifyDeleteTransaction'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelStatistic()->deleteTransaction($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        public function fetchUpdateTransaction() {
            $paramsValidation = ['functionValidData' => 'verifyUpdateTransaction'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation );

            $db = $dataRequest['dataBase'];
            $successReq = $this->getModelStatistic()->updateTransaction($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['isSuccessRequest' => $successReq]);
        }

        // get data
        public function fetchDataTrsBySearch() {
            $paramsValidation = ['functionValidData' => 'verifyGetDataTrsBySearch'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation );

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getDataTrsBySearch($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchTrsMonthByDay() {
            $paramsValidation = ['functionValidData' => 'verifyGetTrsMonthByDay'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getTrsMonthByDay($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchThresholdByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetThresholdByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getThresholdByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchNLastTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetNLastTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getNLastTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchTotalTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetTotalTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getTotalTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchBiggestTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getBiggestTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getYearListTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetYearListTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getYearListTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getTotalTrsByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetTotalTrsByYear'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getTotalTrsByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getBiggestTrsByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestTrsByYear'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getBiggestTrsByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getBiggestMonthByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestMonthByYear'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getBiggestMonthByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getYearListTrsByCategories() {
            $paramsValidation = ['functionValidData' => 'verifyGetYearListTrsByCategories'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getYearListTrsByCategories($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getTopYearCategories() {
            $paramsValidation = ['functionValidData' => 'verifyGetTopYearCategories'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatistic()->getTopYearCategories($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
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