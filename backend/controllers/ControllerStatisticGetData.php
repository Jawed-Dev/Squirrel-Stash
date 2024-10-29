<?php
    require_once './controllers/ControllerMain.php';
    require_once "./views/ViewStatistic.php";
    require_once './models/ModelStatisticGetDataYear.php';
    require_once './models/ModelStatisticGetDataMonth.php';

    interface I_ControllerStatisticGetData {
        function getControllerMain();
        function getModelStatisticDataYear();
        function getModelStatisticDataMonth();

        // get data
        function getTotalTrsMonthByDay();
        function getNLastTrsByMonth();
        function getThresholdByMonth();
        function getTotalTrsByMonth();
        function getBiggestTrsByMonth();
        function getDataTrsBySearch();

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
        * @return ModelStatisticGetDataYear
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
        /**
        * @return ViewStatistic
        */
        public function getViewStatistic() {
            if (!$this->ViewStatistic) $this->ViewStatistic = new ViewStatistic();
            return $this->ViewStatistic;
        }

        public function getTotalTrsMonthByDay() {
            $paramsValidation = ['functionValidData' => 'verifygetTotalTrsMonthByDay'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getTotalTrsMonthByDay($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getDataTrsBySearch() {
            $paramsValidation = ['functionValidData' => 'verifyGetDataTrsBySearch'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation );

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getDataTrsBySearch($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }
       

        public function getThresholdByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetThresholdByMonth'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getThresholdByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getNLastTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetNLastTrsByMonth'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getNLastTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getTotalTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetTotalTrsByMonth'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getTotalTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getBiggestTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestTrsByMonth'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataMonth()->getBiggestTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getYearListTrsByMonth() {
            $paramsValidation = ['functionValidData' => 'verifyGetYearListTrsByMonth'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getYearListTrsByMonth($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        public function getTotalTrsByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetTotalTrsByYear'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getTotalTrsByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getBiggestTrsByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestTrsByYear'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getBiggestTrsByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getBiggestMonthByYear() {
            $paramsValidation = ['functionValidData' => 'verifyGetBiggestMonthByYear'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getBiggestMonthByYear($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getYearListTrsByCategories() {
            $paramsValidation = ['functionValidData' => 'verifyGetYearListTrsByCategories'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getYearListTrsByCategories($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

        function getTopYearCategories() {
            $paramsValidation = ['functionValidData' => 'verifyGetTopYearCategories'];
            $dataRequest = $this->getControllerMain()->getValidateDataForController($paramsValidation);

            $db = $dataRequest['dataBase'];
            $data = $this->getModelStatisticDataYear()->getTopYearCategories($db, $dataRequest);
            // log ici ?
            $this->getViewStatistic()->renderJson(['data' => $data]);
        }

    }
?>