<?php

    use Model\Database;

    try {
        require_once './Controller/ControllerBase.php';
        require_once './Controller/ControllerStatistic.php';
        require_once './Controller/ControllerUser.php';
        require_once './Service/ServiceContainer.php';
        require_once './Model/Database.php';

    
        $ContainerServices = new ContainerServices;
    
        // Service ControllerBase
        $ContainerServices->registerService('ServiceBase', function($ContainerServices) {
            return new ControllerBase($ContainerServices);
        });
        // Service ControllerUser        
        $ContainerServices->registerService('ServiceUser', function($ContainerServices) {
            return new ControllerUser($ContainerServices);
        });
        // Service ControllerStatistic        
        $ContainerServices->registerService('ControllerStatistic', function($ContainerServices) {
            return new ControllerStatistic($ContainerServices);
        });
        // Service Connexion Database
        $ContainerServices->registerService('Database', function() {
            return \Model\Database::getConnection();
        });

        // instances 
        /** 
        * @var ControllerBase 
        */
        $ControllerBase = $ContainerServices->get('ServiceBase');
        /** 
        * @var ControllerUser 
        */
        $ControllerUser = $ContainerServices->get('ServiceUser');
        /** 
        * @var ControllerStatistic 
        */
        $ControllerStatistic = $ContainerServices->get('ControllerStatistic');


        //
        $tokenJwt = $ControllerBase->getBearerToken();

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
    
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json");


        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!empty($_GET['page'])) {
                switch($_GET['page']) {
                    case 'pageIndex': {
                        $ControllerBase->renderPageIndex($tokenJwt);
                        break;
                    }
                    case 'pageLogin': {
                        $ControllerUser->renderPageLogin($tokenJwt);
                        break;
                    }
                    case 'pageDashboard': {
                        $ControllerStatistic->renderPageDashboard($tokenJwt);
                        break;
                    }
                    default: {
                        echo json_encode(['message' => 'Page not found', 'status' => 404]);
                        break;
                    }
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            if(!empty($_GET['form'])) {
                switch($_GET['form']) {
                    case 'formLogin': {

                        $dataPost = file_get_contents('php://input');
                        $ControllerUser->handleSuccessLogin($dataPost);

                        // ob_start();
                        // var_dump($dataPost);
                        // $debugInfo = ob_get_clean();

                        
                        // echo json_encode([
                        //     'test' => 1,
                        //     'debug' => $debugInfo
                        // ]);
                        
                        break;
                    }
                }
            }
        }
    }
    catch(Exception $error) {
        echo json_encode([
            'debug' => $error
        ]);
    }

?>