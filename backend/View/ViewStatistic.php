<?php
    interface I_ViewStatistic {
        function renderJson($dataPage);
    }

    class ViewStatistic implements I_ViewStatistic {
        function renderJson($data) {
            echo json_encode($data);
        }
    }
?>