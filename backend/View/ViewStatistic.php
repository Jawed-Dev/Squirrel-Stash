<?php

    interface I_ViewStatistic {
        function renderJson($dataPage);
    }

    class ViewStatistic implements I_ViewStatistic {
        function renderJson($dataPage) {
            echo json_encode($dataPage);
        }
    }
?>