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
                        case 'getlistTrsMonthByDay': {
                            getControllerMain()->getControllerStatisticGetData()->fetchTrsMonthByDay();
                            break;
                        }
                        case 'getThresholdByMonth' : {
                            getControllerMain()->getControllerStatisticGetData()->fetchThresholdByMonth();
                            break;
                        }
                        case 'getLastNTransactions' : {
                            getControllerMain()->getControllerStatisticGetData()->fetchNLastTrsByMonth();
                            break;
                        }
                        case 'getTotalTrsByMonth' : {
                            getControllerMain()->getControllerStatisticGetData()->fetchTotalTrsByMonth();
                            break;
                        }
                        case 'getBiggestTrsByMonth' : {
                            getControllerMain()->getControllerStatisticGetData()->fetchBiggestTrsByMonth();
                            break;
                        }
                        case 'getDataTrsBySearch' : {
                            getControllerMain()->getControllerStatisticGetData()->fetchDataTrsBySearch();
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
                        case 'IsValidResetPassToken' : {
                            getControllerMain()->getControllerUser()->fetchIsValidResetPassToken();
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