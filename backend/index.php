<?php
    use Model\Database;
    
    try {
        require_once './Controller/ControllerMain.php';
        require_once './Controller/ControllerStatistic.php';
        require_once './Controller/ControllerUser.php';
        require_once './Service/ServiceContainer.php';
        require_once './Model/Database.php';

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
            return \Model\Database::getConnection();
        });

        // Instances 
        /** 
        * @var ControllerMain 
        */
        $ControllerMain = $ContainerServices->get('ControllerMain');
        /** 
        * @var ControllerUser 
        */
        $ControllerUser = $ContainerServices->get('ControllerUser');
        /** 
        * @var ControllerStatistic 
        */
        $ControllerStatistic = $ContainerServices->get('ControllerStatistic');


    
        $tokenJwt = $ControllerMain->getBearerTokenJwt();
    
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json");

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
                        echo json_encode(['message' => 'Page not found', 'status' => 404]);
                        break;
                    }
                }
            }
        }
        // POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            if(!empty($_GET['form'])) {
                switch($_GET['form']) {
                    case 'formLogin': {
                        $dataPost = file_get_contents('php://input');
                        $ControllerUser->handleSuccessLogin($dataPost);
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