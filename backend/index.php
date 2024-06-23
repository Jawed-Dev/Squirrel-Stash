<?php
    //use model\Database;
    try {
        require_once './config.php';
        require_once './controller/controllerMain.php';

        $allowedOrigin = FRONT_BASE_URL;
        $origin = isset($_SERVER['HTTP_X_CUSTOM_ORIGIN']) ? $_SERVER['HTTP_X_CUSTOM_ORIGIN'] : '';
        if ($origin !== $allowedOrigin) throw new Exception('Erreur CORS');

        header("Access-Control-Allow-Origin: ".FRONT_BASE_URL);
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json");

        // ControllerMain 
        $ControllerMain = new ControllerMain();

        // Request get Pages
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!empty($_GET['page'])) {
                switch($_GET['page']) {
                    case 'pageIndex': {
                        $ControllerMain->preparePageIndex();
                        break;
                    }
                    case 'pageLogin': {
                        $ControllerMain->getControllerUser()->preparePageLogin();
                        break;
                    }
                    case 'pageDashboard': {
                        $ControllerMain->getControllerStatistic()->preparePageDashboard();
                        break;
                    }
                    default: {
                        $ControllerMain->sendJsonResponse(['message' => 'Page not found', 'status' => 404]);
                        break;
                    }
                }
            }
        }

        // Request get Data
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            if(!empty($_GET['getData'])) {
                switch($_GET['getData']) {
                    // auth
                    case 'formLogin': {
                        $ControllerMain->getControllerUser()->handleSuccessLogin();
                        break;
                    }
                    // statistic
                    case 'getlistTrsByMonth': {
                        $ControllerMain->getControllerStatistic()->fetchTrsMonthByDay();
                        break;
                    }
                    case 'getThresholdByMonth' : {
                        $ControllerMain->getControllerStatistic()->fetchThresholdByMonth();
                        break;
                    }
                    case 'getLastNTransactions' : {
                        $ControllerMain->getControllerStatistic()->fetchNLastTrsByMonth();
                        break;
                    }
                    case 'getTotalTrsByMonth' : {
                        $ControllerMain->getControllerStatistic()->fetchTotalTrsByMonth();
                        break;
                    }
                    case 'getBiggestTrsByMonth' : {
                        $ControllerMain->getControllerStatistic()->fetchBiggestTrsByMonth();
                        break;
                    }
                }
            }
        }

        // Request setData
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            if(!empty($_GET['setData'])) {
                switch($_GET['setData']) {
                    // auth
                    // statistic
                    case 'getlistTrsByMonth': {
                        $ControllerMain->getControllerStatistic()->fetchThresholdByMonth();
                        break;
                    }
                    case 'amountThresholdByMonth' : {
                        
                    }
                }
            }
        }
    }
    catch(Exception $error) {
        echo json_encode(['debug' => $error]);
        die;
    }

    finally {
        if (empty($ControllerMain)) return null;
        $db = $ControllerMain->getDatabase();
        if($db !== null) $db = null;
    }

?>