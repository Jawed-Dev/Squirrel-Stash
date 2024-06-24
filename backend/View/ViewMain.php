<?php
    //namespace View;

    interface I_ViewMain {
        function renderPageIndexJson($dataPage);
    }

    class ViewMain implements I_ViewMain  {
        
        function renderPageIndexJson($dataPage) {
            echo json_encode([
                'isSessionActive' => $dataPage['isSessionActive'],
            ]);
        }
    }

?>