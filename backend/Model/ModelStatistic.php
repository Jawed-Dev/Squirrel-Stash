<?php

    require_once './controller/ControllerMain.php';

    interface I_ModelStatistic {
        function getTrsMonthByDay($db, $data);
        function getTransactionsDay($db, $userId);
        function getNLastTrsByMonth ($db, $data);
        function getLastDayByMonth($year, $month);
        function getThresholdByMonth($db, $data);
        function getTotalTrsByMonth($db, $data);
        function getBiggestTrsByMonth($db, $data);
    }

    class ModelStatistic implements I_ModelStatistic {

        public function getTrsMonthByDay ($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

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
                number_day.day <= :last_day_of_month
            GROUP BY 
                number_day.day
            ORDER BY 
                number_day.day
            ";
            $lastDayMonth = $this->getLastDayByMonth($dataQuery['selectedYear'], $dataQuery['selectedMonth']); 

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':last_day_of_month',  $lastDayMonth, PDO::PARAM_INT);
            $query->execute();
            $listTrsMonthByDay = $query->fetchAll(PDO::FETCH_ASSOC);
            return $listTrsMonthByDay;
        }

        public function getThresholdByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            //var_dump($data);

            $reqSql = "SELECT threshold_amount
            FROM threshold
            WHERE threshold_user_id = :userId
            AND YEAR(threshold_date_time) = :year
            AND MONTH(threshold_date_time) = :month
            ORDER BY threshold_date_time DESC
            LIMIT 1;
            ";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':month',  $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->execute();
            $tresholdAmount = $query->fetch(PDO::FETCH_ASSOC);
            //var_dump($tresholdAmount);
            return $tresholdAmount;
        }

        public function getNLastTrsByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            //var_dump($data);

            $reqSql = "SELECT 
                *,
                DATE_FORMAT(transaction_date_time, '%d/%m/%Y') as formatted_date,
                (SELECT COUNT(*)
                    FROM transaction AS sub
                    WHERE sub.transaction_user_id = transaction.transaction_user_id
                    AND YEAR(sub.transaction_date_time) = YEAR(transaction.transaction_date_time)
                    AND MONTH(sub.transaction_date_time) = MONTH(transaction.transaction_date_time)
                    AND sub.transaction_name = transaction.transaction_name
                    AND sub.transaction_category = transaction.transaction_category
                ) AS count_transaction
                
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date_time) = :year
                AND MONTH(transaction_date_time) = :month
                AND transaction_category = :category
                ORDER BY transaction_date_time DESC
                LIMIT :limit
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':category',  $dataQuery['category'], PDO::PARAM_STR);
            $query->bindValue(':limit',  $dataQuery['limitValue'], PDO::PARAM_INT);
            $query->execute();
            $listTransactions = $query->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($listTransactions);
            return $listTransactions;
        }

        public function getBiggestTrsByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            //var_dump($data);

            $reqSql = "SELECT 
                transaction_amount,
                transaction_name
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date_time) = :year
                AND MONTH(transaction_date_time) = :month
                AND transaction_category = :category
                ORDER BY transaction_amount DESC
                LIMIT 1
            ";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':category',  $dataQuery['category'], PDO::PARAM_STR);
            $query->execute();

            $biggestTransaction = $query->fetch(PDO::FETCH_ASSOC);
            return $biggestTransaction;
        }

        public function getTotalTrsByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            //var_dump($data);

            $reqSql = "SELECT 
                SUM(transaction_amount) AS total_transactions
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date_time) = :year
                AND MONTH(transaction_date_time) = :month
            ";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->execute();
            $totalTransactions = $query->fetch(PDO::FETCH_ASSOC);
            //var_dump($listTransactions);
            return $totalTransactions;
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

        // Date
        public function getLastDayByMonth($year, $month) {
            $date = new DateTime("$year-$month-01");
            $date->modify('last day of this month');
            return $date->format('d');
        }
    }
?>