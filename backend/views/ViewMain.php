<?php
    //namespace View;

    interface I_ViewMain {
        function renderJson($dataPage);
    }

    class ViewMain implements I_ViewMain  {
        
        function renderJson($dataPage) {
            echo json_encode([
                'isSessionActive' => $dataPage['isSessionActive'],
            ]);
        }
    }

?>