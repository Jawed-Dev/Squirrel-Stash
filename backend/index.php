<?php
    try {
        require_once './config.php';
        require_once './controller/controllerMain.php';

        $allowedOrigin = FRONT_BASE_URL;
        $origin = isset($_SERVER['HTTP_X_CUSTOM_ORIGIN']) ? $_SERVER['HTTP_X_CUSTOM_ORIGIN'] : '';
        //if ($origin !== $allowedOrigin) throw new Exception('Erreur CORS'); A REACRIVER

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
                    case 'pageUser': {
                        getControllerMain()->getControllerUser()->authorizePageUser();
                        break;
                    }
                    case 'pageResetPass': {
                        getControllerMain()->getControllerUser()->authorizePageResetPassword();
                        break;
                    }
                    case 'pageTransactions': {
                        getControllerMain()->getControllerStatistic()->authorizePageTransactions();
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
                    // user
                    case 'getHandleLogin': {
                        getControllerMain()->getControllerUser()->handleSuccessLogin();
                        break;
                    }
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
                    case 'getDataTrsBySearch' : {
                        getControllerMain()->getControllerStatistic()->fetchDataTrsBySearch();
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
                   
                }
            }
        }

        // Request actionData
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            if(!empty($_GET['actionData'])) {
                switch($_GET['actionData']) {
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
                    case 'sendUpdateMail' : {
                        getControllerMain()->getControllerUser()->sendUpdateMail();
                        break;
                    }
                    case 'updatePasswordByToken' : {
                        getControllerMain()->getControllerUser()->updatePasswordByToken();
                        break;
                    }
                    case 'updatePasswordByUserId' : {
                        getControllerMain()->getControllerUser()->updatePasswordByUserId();
                        break;
                    }
                    
                    case 'updateEmail' : {
                        getControllerMain()->getControllerUser()->updateEmail();
                        break;
                    }
                    case 'updateUserProfil' : {
                        getControllerMain()->getControllerUser()->updateUserProfil();
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