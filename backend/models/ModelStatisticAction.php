<?php
    interface I_ModelStatisticAction {
        // action
        function updateThresholdByMonth($db, $data);
        function addThresholdByMonth($db, $data);
        function addTransaction($db, $data);
        function deleteTransaction($db, $data);
        function updateTransaction($db, $data);
    }

    class ModelStatisticAction implements I_ModelStatisticAction {

        public function addTransaction($db, $data) {
            // data
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            // request
            $reqSql = 
            "INSERT INTO transaction
            (   
                transaction_user_id, 
                transaction_amount, 
                transaction_category, 
                transaction_type, 
                transaction_date, 
                transaction_note
            )
            VALUES (:userId, :amount, :trsCategory, :trsType, :trsDate, :note)";
            $query = $db->prepare($reqSql);
            // Binds Values
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':amount',  $dataQuery['transactionAmount'], PDO::PARAM_STR);
            $query->bindValue(':trsCategory',  $dataQuery['transactionCategory'], PDO::PARAM_STR);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->bindValue(':trsDate',  $dataQuery['transactionDate'], PDO::PARAM_STR);
            $query->bindValue(':note',  $dataQuery['transactionNote'], PDO::PARAM_STR);
            $query->execute();
            // successRequest
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
            $query->bindValue(':amount',  $dataQuery['transactionAmount'], PDO::PARAM_STR);
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

        public function addThresholdByMonth($db, $data) {
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

        
        // Date
        public function getLastDayByMonth($year, $month) {
            $date = new DateTime("$year-$month-01");
            $date->modify('last day of this month');
            return $date->format('d');
        }
    }
?>