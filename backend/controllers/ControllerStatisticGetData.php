<?php
    require_once './Controllers/ControllerMain.php';
    require_once "./views/viewStatistic.php";
    require_once './models/ModelStatisticGetDataYear.php';
    require_once './models/ModelStatisticGetDataMonth.php';

    interface I_ControllerStatisticGetData {
        function getControllerMain();
        function getModelStatisticDataYear();
        function getModelStatisticDataMonth();

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
    }

    class ControllerStatisticGetData implements I_ControllerStatisticGetData {
        private $ControllerMain;
        private $ViewStatistic;
        private $ModelStatisticDataYear;
        private $ModelStatisticGetDataMonth;

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
        public function getModelStatisticDataYear() {
            if (!$this->ModelStatisticDataYear) $this->ModelStatisticDataYear = new ModelStatisticGetDataYear();
            return $this->ModelStatisticDataYear;
        }

        // Model 
        /**
        * @return ModelStatisticGetDataMonth
        */
        public function getModelStatisticDataMonth() {
            if (!$this->ModelStatisticGetDataMonth) $this->ModelStatisticGetDataMonth = new ModelStatisticGetDataMonth();
            return $this->ModelStatisticGetDataMonth;
        }

        // View 
        public function getViewStatistic() {
            if (!$this->ViewStatistic) $this->ViewStatistic = new ViewStatistic();
            return $this->ViewStatistic;
        }

        public function fetchTrsMonthByDay() {
            $paramsValidation = ['functionValidData' => 'verifyGetTrsMonthByDay'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getTrsMonthByDay($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchDataTrsBySearch() {
            $paramsValidation = ['functionValidData' => 'verifyGetDataTrsBySearch'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation );

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getDataTrsBySearch($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }
       

        public function fetchThresholdByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetThresholdByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getThresholdByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchNLastTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetNLastTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getNLastTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchTotalTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetTotalTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getTotalTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function fetchBiggestTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getBiggestTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getYearListTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetYearListTrsByMonth'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getYearListTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getTotalTrsByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetTotalTrsByYear'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getTotalTrsByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getBiggestTrsByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestTrsByYear'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getBiggestTrsByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getBiggestMonthByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestMonthByYear'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getBiggestMonthByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getYearListTrsByCategories() {
            $paramsValidation = ['functionValidData' => 'verifyGetYearListTrsByCategories'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getYearListTrsByCategories($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getTopYearCategories() {
            $paramsValidation = ['functionValidData' => 'verifyGetTopYearCategories'];
            $dataRequest = $this->getControllerMain()->validateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getTopYearCategories($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

    }
?>