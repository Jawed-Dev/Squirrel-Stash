<?php

    require_once "./controller/controllerUser.php";
    require_once "./view/viewStatistic.php";
    require_once './model/ModelStatistic.php';

    interface I_ControllerStatistic {
        // Main Controller lazy loading
        function getControllerMain();
        // Model lazy loading
        function getModelStatistic();
        // View lazy loading
        function getViewStatistic();
        // Statistic
        function fetchTransactionsMonth($data);
        function fetchTransactionsDay($userId);
        function fetchNRecentTrsMonth($userId);
        // Prepare page
        function preparePageDashboard($tokenJwt);
    }

    
    class ControllerStatistic implements I_ControllerStatistic {

        private $ContainerServices;
        private $ControllerMain;
        private $ViewStatistic;
        private $ModelStatistic;

        public function __construct($ContainerServices) {
            $this->ContainerServices = $ContainerServices;
        }

        // Main Controller lazy loading
        /**
        * @return ControllerMain
        */
        public function getControllerMain() {
            if ($this->ControllerMain === null) $this->ControllerMain = $this->ContainerServices->getService('ControllerMain');
            return $this->ControllerMain;
        }

        // Model lazy loading
        public function getModelStatistic() {
            if (!$this->ModelStatistic) $this->ModelStatistic = new ModelStatistic();
            return $this->ModelStatistic;
        }

        // View lazy loading
        public function getViewStatistic() {
            if (!$this->ViewStatistic) $this->ViewStatistic = new ViewStatistic();
            return $this->ViewStatistic;
        }

        // Statistic
        public function fetchTransactionsMonth($data) {
            $tokenJwt = $data['tokenJwt'];
            $dataForm = $data['dataPost'];

            $db = $this->getControllerMain()->getDatabase();
            $userId = $this->getControllerMain()->getControllerUser()->getUserIdIntoJwt($tokenJwt);

            $dataModel = [
                'dataPost' => $dataForm,
                'userId' => $userId
            ];

            // var_dump($db);
            // var_dump($dataModel);

            $listTransactionsMonth = $this->getModelStatistic()->getTransactionsMonth($db, $dataModel);
            //var_dump($listTransactionsMonth);
            //return $listTransactionsMonth;

            echo json_encode(['data' => $listTransactionsMonth]);
        }

        public function fetchTransactionsDay($userId) {
            $db = $this->getControllerMain()->getDatabase();
            $listTransactionsDay = $this->ModelStatistic->getTransactionsMonth($db, $userId);
            return $listTransactionsDay;
        }

        public function fetchNRecentTrsMonth($userId) {
            $db = $this->getControllerMain()->getDatabase();
            $listTransactionsDay = $this->ModelStatistic->getTransactionsMonth($db, $userId);
            return $listTransactionsDay;
        }

        // Prepare Pages
        public function preparePageDashboard($tokenJwt) {
            $isUserConnected = $this->getControllerMain()->getControllerUser()->isUserConnected($tokenJwt);
            $dataPage = [
                'isUserConnected' => $isUserConnected,
            ];  
            $this->getViewStatistic()->renderPageDashboard($dataPage);
        }


    }


?>