<?php
    try {
        // config
        require_once './config.php';
        require_once './Controllers/controllerMain.php';

        // CORS config
        $allowedOrigin = FRONT_BASE_URL;
        $origin = isset($_SERVER['HTTP_X_CUSTOM_ORIGIN']) ? $_SERVER['HTTP_X_CUSTOM_ORIGIN'] : '';
        if ($origin !== $allowedOrigin) throw new Exception('Erreur CORS'); 
        header("Access-Control-Allow-Origin: ".FRONT_BASE_URL);
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json");

        // env loader
        require_once './vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // Controller Main
        /**
        * @return ControllerMain
        */
        function getControllerMain() {
            $ControllerMain = new ControllerMain();
            return $ControllerMain;
        }

        // Router getPage
        getControllerMain()->getRouterPage()->hanglePageRouter();

        // Router getData
        getControllerMain()->getRouterGetData()->handleRouterGetData();

        // Router action
        getControllerMain()->getRouterAction()->handleRouterAction();
        
    }
    catch(Exception $error) {
        http_response_code(401);
        echo json_encode(['debug' => $error]);
        die;
    }

    finally {
        $db = getControllerMain()->getDatabase();
        if($db !== null) $db = null;
    }

?>