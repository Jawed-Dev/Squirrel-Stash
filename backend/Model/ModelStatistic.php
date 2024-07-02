<?php

    require_once './controller/ControllerMain.php';

    interface I_ModelStatistic {
        //get
        function getTrsMonthByDay($db, $data);
        function getNLastTrsByMonth ($db, $data);
        function getLastDayByMonth($year, $month);
        function getThresholdByMonth($db, $data);
        function getTotalTrsByMonth($db, $data);
        function getBiggestTrsByMonth($db, $data);
        function isThresholdExistByMonth($db, $data);
        // action
        function updateThresholdByMonth($db, $data);
        function insertThresholdByMonth($db, $data);
        function insertTransaction($db, $data);
        function deleteTransaction($db, $data);
        function updateTransaction($db, $data);
    }

    class ModelStatistic implements I_ModelStatistic {
        public function insertTransaction($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = 
            "INSERT INTO transaction
            (transaction_user_id , transaction_amount, transaction_category, transaction_type, transaction_date, transaction_note)
            VALUES (:userId, :amount, :trsCategory, :trsType, :trsDate, :note)
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':amount',  $dataQuery['transactionAmount'], PDO::PARAM_INT);
            $query->bindValue(':trsCategory',  $dataQuery['transactionCategory'], PDO::PARAM_STR);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->bindValue(':trsDate',  $dataQuery['transactionDate'], PDO::PARAM_STR);
            $query->bindValue(':note',  $dataQuery['transactionNote'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function updateTransaction($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = 
            "UPDATE transaction
            SET 
                transaction_amount = :amount,
                transaction_category = :trsCategory,
                transaction_type = :trsType,
                transaction_date = :trsDate, 
                transaction_note = :note
            WHERE transaction_user_id = :userId AND transaction_id = :trsId
            ";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':trsId',  $dataQuery['transactionId'], PDO::PARAM_INT);
            $query->bindValue(':amount',  $dataQuery['transactionAmount'], PDO::PARAM_INT);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->bindValue(':trsCategory',  $dataQuery['transactionCategory'], PDO::PARAM_STR);
            $query->bindValue(':trsDate',  $dataQuery['transactionDate'], PDO::PARAM_STR);
            $query->bindValue(':note',  $dataQuery['transactionNote'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function deleteTransaction($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = 
            "DELETE FROM transaction
            WHERE 
                transaction_user_id = :userId
                AND transaction_id = :transactionId
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':transactionId',  $dataQuery['transactionId'], PDO::PARAM_INT);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function insertThresholdByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = "INSERT INTO threshold
            (threshold_user_id, threshold_amount, threshold_date)
            VALUES (:userId, :amount, :timeNow)
            ";

            $selectedDateWithFirstDay = sprintf("%04d-%02d-01", $dataQuery['selectedYear'], $dataQuery['selectedMonth']);

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':timeNow',  $selectedDateWithFirstDay, PDO::PARAM_STR);
            $query->bindValue(':amount',  $dataQuery['thresholdAmount'], PDO::PARAM_INT);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function updateThresholdByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = "UPDATE threshold
            SET threshold_date = :timeNow,
            threshold_amount = :amount
            WHERE threshold_user_id = :userId
            AND MONTH(threshold_date) = :month
            AND YEAR(threshold_date) = :year
            LIMIT 1
            ";
            
            $selectedDateWithFirstDay = sprintf("%04d-%02d-01", $dataQuery['selectedYear'], $dataQuery['selectedMonth']);
            
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':timeNow', $selectedDateWithFirstDay, PDO::PARAM_STR);
            $query->bindValue(':amount',  $dataQuery['thresholdAmount'], PDO::PARAM_INT);
            $query->bindValue(':month',  $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

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
                t.transaction_type, 
                t.transaction_category, 
                t.transaction_amount, 
                t.transaction_note 
            FROM 
                number_day 
            LEFT JOIN 
                transaction t 
            ON 
                DAY(t.transaction_date) = number_day.day 
                AND MONTH(t.transaction_date) = :month 
                AND YEAR(t.transaction_date) = :year 
                AND t.transaction_user_id = :userId
                AND t.transaction_type = :trsType
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
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->bindValue(':last_day_of_month',  $lastDayMonth, PDO::PARAM_INT);
            $query->execute();
            $listTrsMonthByDay = $query->fetchAll(PDO::FETCH_ASSOC);
            return $listTrsMonthByDay;
        }

        public function isThresholdExistByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = "SELECT EXISTS (
                SELECT 1 FROM threshold
                WHERE threshold_user_id = :userId
                AND MONTH(threshold_date) = :month
                AND YEAR(threshold_date) = :year
                ) AS thresholdExist;
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':month',  $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->execute();
            $isTresholdExist = $query->fetch(PDO::FETCH_ASSOC);
            return ($isTresholdExist['thresholdExist']) ? true : false;
        }

        public function getThresholdByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = "SELECT threshold_amount
            FROM threshold
            WHERE threshold_user_id = :userId
            AND threshold_date <= STR_TO_DATE(CONCAT(:year, '-', :month, '-01'), '%Y-%m-%d')
            ORDER BY threshold_date DESC
            LIMIT 1;
            ";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':month',  $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->execute();
            $thresholdAmount = $query->fetch(PDO::FETCH_ASSOC);
            return $thresholdAmount;
        }

        public function getNLastTrsByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT 
                transaction_id,
                transaction_user_id,
                transaction_amount,
                transaction_type,
                transaction_category,
                transaction_date,
                transaction_note,
                DATE_FORMAT(transaction_date, '%d/%m/%Y') as formatted_date,
                (SELECT COUNT(*)
                    FROM transaction AS sub
                    WHERE sub.transaction_user_id = transaction.transaction_user_id
                    AND YEAR(sub.transaction_date) = YEAR(transaction.transaction_date)
                    AND MONTH(sub.transaction_date) = MONTH(transaction.transaction_date)
                    AND sub.transaction_type = transaction.transaction_type
                    AND sub.transaction_category = transaction.transaction_category
                ) AS count_transaction
                
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date) = :year
                AND MONTH(transaction_date) = :month
                AND transaction_type = :trsType
                ORDER BY transaction_date DESC
                LIMIT :limit
            ";

            define("LIMIT_RESULT", 5);

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':trsType', $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->bindValue(':limit', LIMIT_RESULT, PDO::PARAM_INT);
            $query->execute();
            $listTransactions = $query->fetchAll(PDO::FETCH_ASSOC);
            return $listTransactions;
        }

        public function getBiggestTrsByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT 
                transaction_amount,
                transaction_category
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date) = :year
                AND MONTH(transaction_date) = :month
                AND transaction_type = :trsType
                ORDER BY transaction_amount DESC
                LIMIT 1
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->execute();

            $biggestTransaction = $query->fetch(PDO::FETCH_ASSOC);
            return $biggestTransaction;
        }

        public function getTotalTrsByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT 
                SUM(transaction_amount) AS total_transactions
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date) = :year
                AND MONTH(transaction_date) = :month
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->execute();
            $totalTransactions = $query->fetch(PDO::FETCH_ASSOC);
            return $totalTransactions;
        }

        // Date
        public function getLastDayByMonth($year, $month) {
            $date = new DateTime("$year-$month-01");
            $date->modify('last day of this month');
            return $date->format('d');
        }
    }
?>