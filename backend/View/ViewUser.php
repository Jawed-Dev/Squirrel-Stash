<?php

    interface I_ViewUser {
        function renderPageLogin($dataPage);
    }

    class ViewUser implements I_ViewUser {
        function renderPageLogin($dataPage) {
            echo json_encode([
                'isUserConnected' => $dataPage['isUserConnected'],
            ]);
        }
    }
?>