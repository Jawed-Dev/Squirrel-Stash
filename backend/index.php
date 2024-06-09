<?php
    session_start();

    try {
        require_once './Controller/ControllerBase.php';
       // require_once './Controller/ControllerStatistic.php';
        require_once './Controller/ControllerUser.php';
        require_once './service/ServiceContainer.php';
    
        $ContainerServices = new ContainerServices;
    
        // Enregistrement de ControllerBase
        $ContainerServices->register('ServiceBase', function($ContainerServices) {
            return new ControllerBase($ContainerServices);
        });
    
        //Enregistrement de ControllerUser
        $ContainerServices->register('ServiceUser', function($ContainerServices) {
            return new ControllerUser($ContainerServices);
        });
    
        // Récupération des instances de contrôleurs pour utilisation
        $ControllerBase = $ContainerServices->get('ServiceBase');
        $ControllerUser = $ContainerServices->get('ServiceUser');
    
    
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json");
    
    
        if(!empty($_GET['page'])) {
            switch($_GET['page']) {
                case 'pageIndex': {
                    $ControllerBase->renderPageIndex();
                    break;
                }
                case 'pageLogin': {
                    $ControllerUser->renderPageLogin();
                    break;
                }
                case 'dashboard': {
                    $ControllerStatistic->renderPageDashboard();
                    break;
                }
                default: {
                    echo json_encode(['message' => 'Page not found', 'status' => 404]);
                    break;
                }
            }
        }
    }
    catch(Exception $e) {

    }

?>