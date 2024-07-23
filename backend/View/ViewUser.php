<?php

    interface I_ViewUser {
        function renderJson($dataPage);
    }

    class ViewUser implements I_ViewUser {
        function renderJson($dataPage) {
            echo json_encode($dataPage);
        }
    }
?>