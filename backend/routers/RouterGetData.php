<?php
    class RouterGetData {
        public function handleRouterGetData() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                if(!empty($_GET['getData'])) {
                    switch($_GET['getData']) {
                        // user
                        case 'getTokenIfSuccessLogin': {
                            getControllerMain()->getControllerUser()->handleSuccessLogin();
                            break;
                        }
                        // user
                        case 'getStateSession' : {
                            getControllerMain()->getControllerUser()->getStateSession();
                            break;
                        }
                        case 'getUserFirstName' : {
                            getControllerMain()->getControllerUser()->getUserFirstName();
                            break;
                        }
                        // statistic
                        case 'getTotalTrsMonthByDay': {
                            getControllerMain()->getControllerStatisticGetData()->getTotalTrsMonthByDay();
                            break;
                        }
                        case 'getThresholdByMonth' : {
                            getControllerMain()->getControllerStatisticGetData()->getThresholdByMonth();
                            break;
                        }
                        case 'getLastNTransactions' : {
                            getControllerMain()->getControllerStatisticGetData()->getNLastTrsByMonth();
                            break;
                        }
                        case 'getTotalTrsByMonth' : {
                            getControllerMain()->getControllerStatisticGetData()->getTotalTrsByMonth();
                            break;
                        }
                        case 'getBiggestTrsByMonth' : {
                            getControllerMain()->getControllerStatisticGetData()->getBiggestTrsByMonth();
                            break;
                        }
                        case 'getDataTrsBySearch' : {
                            getControllerMain()->getControllerStatisticGetData()->getDataTrsBySearch();
                            break;
                        }
                        case 'getDataUserProfil' : {
                            getControllerMain()->getControllerUser()->getDataUserProfil();
                            break;
                        }
                        case 'getUserEmail' : {
                            getControllerMain()->getControllerUser()->getUserEmail();
                            break;
                        }
                        case 'isValidResetPassToken' : {
                            getControllerMain()->getControllerUser()->isValidResetPassToken();
                            break;
                        }
                        case 'getYearListTrsByMonth' : {
                            getControllerMain()->getControllerStatisticGetData()->getYearListTrsByMonth();
                            break;
                        }
                        case 'getTotalTrsByYear' : {
                            getControllerMain()->getControllerStatisticGetData()->getTotalTrsByYear();
                            break;
                        }
                        case 'getBiggestTrsByYear' : {
                            getControllerMain()->getControllerStatisticGetData()->getBiggestTrsByYear();
                            break;
                        }
                        case 'getBiggestMonthByYear' : {
                            getControllerMain()->getControllerStatisticGetData()->getBiggestMonthByYear();
                            break;
                        }
                        case 'getYearListTrsByCategories' : {
                            getControllerMain()->getControllerStatisticGetData()->getYearListTrsByCategories();
                            break;
                        }
                        case 'getTopYearCategories' : {
                            getControllerMain()->getControllerStatisticGetData()->getTopYearCategories();
                            break;
                        }
                    }
                }
            }
        }
    }
?>