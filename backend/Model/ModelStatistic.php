<?php

    require_once './controller/ControllerMain.php';

    interface I_ModelStatistic {
        function getTransactionsMonth($db, $data);
        function getTransactionsDay($db, $userId);
        function getNRecentTrsMonth ($db, $data);
    }

    class ModelStatistic implements I_ModelStatistic {


        public function getTransactionsMonth($db, $data) {
            $userId = $data['userId'];
            $dataPost = $data['dataPost'];

            $reqSql = "SELECT 
                number_day.day AS day, 
                LPAD(:month, 2, '0') AS month, 
                :year AS year,
                COALESCE(SUM(t.transaction_amount), 0) AS total_amount,
                t.transaction_id,
                t.transaction_user_id, 
                t.transaction_name, 
                t.transaction_category, 
                t.transaction_amount, 
                t.transaction_note 
            FROM 
                number_day 
            LEFT JOIN 
                transaction t 
            ON 
                DAY(t.transaction_date_time) = number_day.day 
                AND MONTH(t.transaction_date_time) = :month 
                AND YEAR(t.transaction_date_time) = :year 
                AND t.transaction_user_id = :userId
            WHERE 
                number_day.day <= DAY(LAST_DAY(STR_TO_DATE(CONCAT_WS('-', '01', LPAD(:month, 2, '0'), :year), '%d-%m-%Y')))
            GROUP BY 
                number_day.day, t.transaction_id 
            ORDER BY 
                number_day.day;
            ;";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataPost['monthSelected'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataPost['yearSelected'], PDO::PARAM_INT);
            $query->execute();
            $listTransactions = $query->fetchAll();
            return $listTransactions;
        }

        public function getNRecentTrsMonth ($db, $data) {
            $userId = $data['userId'];
            $numberRecentTrsMonth = 5;

            $reqSql = "SELECT 
                number_day.day AS day, 
                LPAD(:month, 2, '0') AS month, 
                :year AS year,
                COALESCE(SUM(t.transaction_amount), 0) AS total_amount,
                t.transaction_id,
                t.transaction_user_id, 
                t.transaction_name, 
                t.transaction_category, 
                t.transaction_amount, 
                t.transaction_note 
            FROM 
                number_day 
            LEFT JOIN 
                transaction t 
            ON 
                DAY(t.transaction_date_time) = number_day.day 
                AND MONTH(t.transaction_date_time) = :month 
                AND YEAR(t.transaction_date_time) = :year 
                AND t.transaction_user_id = :userId
            WHERE 
                number_day.day <= DAY(LAST_DAY(STR_TO_DATE(CONCAT_WS('-', '01', LPAD(:month, 2, '0'), :year), '%d-%m-%Y')))
            GROUP BY 
                number_day.day, t.transaction_id 
            ORDER BY DESC
                number_day.day;
            LIMIT = :nRecentTransactions;
            ;";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', 06, PDO::PARAM_INT);
            $query->bindValue(':year',  2024, PDO::PARAM_INT);
            $query->bindValue(':nRecentTransactions',  $numberRecentTrsMonth, PDO::PARAM_INT);
            $query->execute();
            $listTransactions = $query->fetchAll(PDO::FETCH_ASSOC);
            return $listTransactions;
        }

        public function getTransactionsDay($db, $userId) {
            $reqSql = "SELECT 
                number_day.day AS day, 
                LPAD(:month, 2, '0') AS month, 
                :year AS year,
                COALESCE(SUM(t.transaction_amount), 0) AS total_amount,
                t.transaction_id,
                t.transaction_user_id, 
                t.transaction_name, 
                t.transaction_category, 
                t.transaction_amount, 
                t.transaction_note 
            FROM 
                number_day 
            LEFT JOIN 
                transaction t 
            ON 
                DAY(t.transaction_date_time) = number_day.day 
                AND MONTH(t.transaction_date_time) = :month 
                AND YEAR(t.transaction_date_time) = :year 
                AND t.transaction_user_id = :userId
            WHERE 
                number_day.day = :day
            GROUP BY 
                number_day.day, t.transaction_id 
            ORDER BY 
                number_day.day;
            ;";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', 6, PDO::PARAM_INT);
            $query->bindValue(':year',  2024, PDO::PARAM_INT);
            $query->bindValue(':day',  19, PDO::PARAM_INT);
            $query->execute();
            $listTransactions = $query->fetchAll();
            return $listTransactions;
        }
    }
?>