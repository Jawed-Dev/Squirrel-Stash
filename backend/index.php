<?php
    //use model\Database;
    try {
        require_once './config.php';
        

        $allowedOrigin = FRONT_BASE_URL;
        $origin = isset($_SERVER['HTTP_X_CUSTOM_ORIGIN']) ? $_SERVER['HTTP_X_CUSTOM_ORIGIN'] : '';
        //var_dump($_SERVER['HTTP_X_CUSTOM_ORIGIN']);
        if ($origin !== $allowedOrigin) throw new Exception('Erreur de CORS');

        header("Access-Control-Allow-Origin: ".FRONT_BASE_URL);
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json");

        require_once './controller/controllerMain.php';
        require_once './controller/controllerStatistic.php';
        require_once './controller/controllerUser.php';
        require_once './service/serviceContainer.php';
        require_once './model/Database.php';
        
        // Container Services
        $ContainerServices = new ContainerServices;

        // Services 
        $ContainerServices->registerService('ControllerMain', function($ContainerServices) {
            return new ControllerMain($ContainerServices);
        });   
        $ContainerServices->registerService('ControllerUser', function($ContainerServices) {
            return new ControllerUser($ContainerServices);
        });  
        $ContainerServices->registerService('ControllerStatistic', function($ContainerServices) {
            return new ControllerStatistic($ContainerServices);
        });
        $ContainerServices->registerService('Database', function() {
            return \model\Database::getConnection();
        });

        // Instances 
        /** 
        * @var ControllerMain 
        */
        $ControllerMain = $ContainerServices->getService('ControllerMain');
        /** 
        * @var ControllerUser 
        */
        $ControllerUser = $ContainerServices->getService('ControllerUser');
        /** 
        * @var ControllerStatistic 
        */
        $ControllerStatistic = $ContainerServices->getService('ControllerStatistic');

        $tokenJwt = $ControllerMain->getBearerTokenJwt();

        // GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!empty($_GET['page'])) {
                switch($_GET['page']) {
                    case 'pageIndex': {
                        $ControllerMain->preparePageIndex($tokenJwt);
                        break;
                    }
                    case 'pageLogin': {
                        $ControllerUser->preparePageLogin($tokenJwt);
                        break;
                    }
                    case 'pageDashboard': {
                        $ControllerStatistic->preparePageDashboard($tokenJwt);
                        break;
                    }
                    default: {
                        $ControllerMain->sendJsonResponse(['message' => 'Page not found', 'status' => 404]);
                        break;
                    }
                }
            }
        }
        // POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            if(!empty($_GET['form'])) {
                switch($_GET['form']) {
                    // auth
                    case 'formLogin': {
                        $dataPost = file_get_contents('php://input');
                        $ControllerUser->handleSuccessLogin($dataPost);
                        break;
                    }
                    // statistic
                    case 'listTransactionsMonth': {
                        $postJson = file_get_contents('php://input');
                        $dataPost = $dataArray = json_decode($postJson, true);

                        
                        $data = [
                            'tokenJwt' => $ControllerMain->decodeJwt($tokenJwt),
                            'dataPost' => $dataPost
                        ];
                        $ControllerStatistic->fetchTransactionsMonth($data);
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
        if (empty($ControllerMain)) return null;
        $db = $ControllerMain->getDatabase();
        if($db !== null) $db = null;
    }

?>