<?php
    try {
        require_once './config.php';
        require_once './controller/controllerMain.php';

        $allowedOrigin = FRONT_BASE_URL;
        $origin = isset($_SERVER['HTTP_X_CUSTOM_ORIGIN']) ? $_SERVER['HTTP_X_CUSTOM_ORIGIN'] : '';
        if ($origin !== $allowedOrigin) throw new Exception('Erreur CORS');

        header("Access-Control-Allow-Origin: ".FRONT_BASE_URL);
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json");

        // Controller Main
        /**
        * @return ControllerMain
        */
        // Model ControllerMain 
        function getControllerMain() {
            $ControllerMain = new ControllerMain();
            return $ControllerMain;
        }

        // Request get Pages
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!empty($_GET['page'])) {
                switch($_GET['page']) {
                    case 'pageIndex': {
                        getControllerMain()->authorizePageIndex();
                        break;
                    }
                    case 'pageLogin': {
                        getControllerMain()->getControllerUser()->authorizePageLogin();
                        break;
                    }
                    case 'pageDashboard': {
                        getControllerMain()->getControllerStatistic()->authorizePageDashboard();
                        break;
                    }
                    case 'pageRegister': {
                        getControllerMain()->getControllerUser()->authorizePageRegister();
                        break;
                    }
                    case 'pageForgotPass': {
                        getControllerMain()->getControllerUser()->authorizePageForgotPass();
                        break;
                    }
                    case 'pageResetPass': {
                        getControllerMain()->getControllerUser()->authorizePageResetPassword();
                        break;
                    }
                    default: {
                        getControllerMain()->sendJsonResponse(['message' => 'Page not found', 'status' => 404]);
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
                    case 'getHandleLogin': {
                        getControllerMain()->getControllerUser()->handleSuccessLogin();
                        break;
                    }
                    case 'getStateSession' : {
                        getControllerMain()->getControllerUser()->getStateSession();
                        break;
                    }
                    // statistic
                    case 'getlistTrsMonthByDay': {
                        getControllerMain()->getControllerStatistic()->fetchTrsMonthByDay();
                        break;
                    }
                    case 'getThresholdByMonth' : {
                        getControllerMain()->getControllerStatistic()->fetchThresholdByMonth();
                        break;
                    }
                    case 'getLastNTransactions' : {
                        getControllerMain()->getControllerStatistic()->fetchNLastTrsByMonth();
                        break;
                    }
                    case 'getTotalTrsByMonth' : {
                        getControllerMain()->getControllerStatistic()->fetchTotalTrsByMonth();
                        break;
                    }
                    case 'getBiggestTrsByMonth' : {
                        getControllerMain()->getControllerStatistic()->fetchBiggestTrsByMonth();
                        break;
                    }
                    case 'IsValidResetPassToken' : {
                        getControllerMain()->getControllerUser()->fetchIsValidResetPassToken();
                        break;
                    }
                }
            }
        }

        // Request setData
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            if(!empty($_GET['setData'])) {
                switch($_GET['setData']) {
                    case 'createAccount': {
                        getControllerMain()->getControllerUser()->fetchInsertUser();
                        break;
                    }
                    case 'saveThreshold': {
                        getControllerMain()->getControllerStatistic()->fetchSaveThreshold();
                        break;
                    }
                    case 'addTransaction' : {
                        getControllerMain()->getControllerStatistic()->fetchInsertTransaction();
                        break;
                    }
                    case 'deleteTransaction' : {
                        getControllerMain()->getControllerStatistic()->fetchDeleteTransaction();
                        break;
                    }
                    case 'updateTransaction' : {
                        getControllerMain()->getControllerStatistic()->fetchUpdateTransaction();
                        break;
                    }
                    case 'sendResetPass' : {
                        getControllerMain()->getControllerUser()->FetchSendResetPassToken();
                        break;
                    }
                    case 'updatePassword' : {
                        getControllerMain()->getControllerUser()->fetchUpdatePassword();
                        break;
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
        //if (empty($ControllerMain)) return null;

        $db = getControllerMain()->getDatabase();
        if($db !== null) $db = null;
    }

?>