<?php

    interface I_ViewStatistic {
        function renderPageDashboard($dataPage);
    }

    class ViewStatistic implements I_ViewStatistic {
        function renderPageDashboard($dataPage) {
            echo json_encode([
                'isSessionActive' => $dataPage['isSessionActive'],
            ]);
        }
    }
?>