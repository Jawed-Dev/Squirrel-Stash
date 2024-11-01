<?php
    interface I_ModelStatisticGetDataMonth {
        //get data month
        function getTotalTrsMonthByDay($db, $data);
        function getNLastTrsByMonth ($db, $data);
        function getLastDayByMonth($year, $month);
        function getThresholdByMonth($db, $data);
        function getTotalTrsByMonth($db, $data);
        function getBiggestTrsByMonth($db, $data);
        function isThresholdExistByMonth($db, $data);
        function getDataTrsBySearch($db, $data);
    }

    class ModelStatisticGetDataMonth implements I_ModelStatisticGetDataMonth {
        public function getTotalTrsMonthByDay ($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            // I use UNION ALL to create a virtual table, for not to overload my database with this functionality
            $reqSql = "SELECT 
                days.day, 
                CONCAT('Jour ', days.day) AS labels, 
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
                (
                    SELECT 1 AS day UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
                    UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
                    UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15
                    UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20
                    UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24 UNION ALL SELECT 25
                    UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30
                    UNION ALL SELECT 31
                ) days
            LEFT JOIN 
                transaction t 
            ON 
                DAY(t.transaction_date) = days.day 
                AND MONTH(t.transaction_date) = :month 
                AND YEAR(t.transaction_date) = :year 
                AND t.transaction_user_id = :userId
                AND t.transaction_type = :trsType
            WHERE 
                days.day <= :last_day_of_month
            GROUP BY 
                days.day
            ORDER BY 
                days.day
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
    
        // public function getTotalTrsMonthByDay ($db, $data) {
        //     $userId = $data['userId'];
        //     $dataQuery = $data['bodyData'];

        //     $reqSql = "SELECT 
        //         number_day.day, 
        //         CONCAT('Jour ', number_day.day) AS labels, 
        //         LPAD(:month, 2, '0') AS month, 
        //         :year AS year,
        //         COALESCE(SUM(t.transaction_amount), 0) AS total_amount,
        //         t.transaction_id,
        //         t.transaction_user_id, 
        //         t.transaction_type, 
        //         t.transaction_category, 
        //         t.transaction_amount, 
        //         t.transaction_note 
        //     FROM 
        //         number_day 
        //     LEFT JOIN 
        //         transaction t 
        //     ON 
        //         DAY(t.transaction_date) = number_day.day 
        //         AND MONTH(t.transaction_date) = :month 
        //         AND YEAR(t.transaction_date) = :year 
        //         AND t.transaction_user_id = :userId
        //         AND t.transaction_type = :trsType
        //     WHERE 
        //         number_day.day <= :last_day_of_month
        //     GROUP BY 
        //         number_day.day
        //     ORDER BY 
        //         number_day.day
        //     ";
        //     $lastDayMonth = $this->getLastDayByMonth($dataQuery['selectedYear'], $dataQuery['selectedMonth']); 

        //     $query = $db->prepare($reqSql);
        //     $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
        //     $query->bindValue(':month', $dataQuery['selectedMonth'], PDO::PARAM_INT);
        //     $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
        //     $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
        //     $query->bindValue(':last_day_of_month',  $lastDayMonth, PDO::PARAM_INT);
        //     $query->execute();
        //     $listTrsMonthByDay = $query->fetchAll(PDO::FETCH_ASSOC);
        //     return $listTrsMonthByDay;
        // }

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
            $whereClauseCommon = 
                "transaction_user_id = :userId
                AND YEAR(transaction_date) = :year
                AND MONTH(transaction_date) = :month
                AND transaction_type = :trsType";

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
                    WHERE 
                        sub.transaction_category = transaction.transaction_category
                        AND $whereClauseCommon
                ) AS count_categories
                FROM 
                    transaction
                WHERE 
                    $whereClauseCommon
                ORDER BY 
                    transaction_date DESC,
                    transaction_id DESC
                LIMIT 
                    :limit
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

        public function getDataTrsBySearch($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            // conditions / binds
            $conditions = [];
            $binds = [];
            $conditions = ["transaction_user_id = :userId"];
            $binds = [':userId' => $userId];

            // order state
            $ORDER_STATE = [
                'DATE' => 0,
                'AMOUNT' => 1,
                'CATEGORY' => 2,
                'ITERATION' => 3
            ];
            // init order
            $orderByField = 'transaction_date';
            $orderType  = 'DESC'; 

            // order desc / asc
            switch($dataQuery['currentOrderSelected']) {
                case $ORDER_STATE['CATEGORY'] : {
                    $orderByField = 'transaction_category';
                    $orderType  = (!$dataQuery['orderAsc']) ? 'DESC' : 'ASC'; 
                    break;
                }
                case $ORDER_STATE['AMOUNT'] : {
                    $orderByField = 'transaction_amount';
                    $orderType  = (!$dataQuery['orderAsc']) ? 'DESC' : 'ASC'; 
                    break;
                }
                case $ORDER_STATE['DATE'] : {
                    $orderByField = 'transaction_date';
                    $orderType  = (!$dataQuery['orderAsc']) ? 'DESC' : 'ASC'; 
                    break;
                }
                case $ORDER_STATE['ITERATION'] : {
                    $orderByField = 'count_categories';
                    $orderType  = (!$dataQuery['orderAsc']) ? 'DESC' : 'ASC'; 
                    break;
                }
            }

            // where conditions
            $this->addConditionQuery($conditions, $binds, 'transaction_date', '>=', ':dateMin', $dataQuery['searchDateRangeDateMin']);
            $this->addConditionQuery($conditions, $binds, 'transaction_date', '<=', ':dateMax', $dataQuery['searchDateRangeDateMax']);
            $this->addConditionQuery($conditions, $binds, 'transaction_category', 'LIKE',':searchCategory', $dataQuery['searchCategory'], true);
            $this->addConditionQuery($conditions, $binds, 'transaction_note', 'LIKE', ':searchNote', $dataQuery['searchNote'], true);
            $this->addConditionQuery($conditions, $binds, 'transaction_amount', '>=', ':amountMin', $dataQuery['searchAmountMin']);
            $this->addConditionQuery($conditions, $binds, 'transaction_amount', '<=', ':amountMax', $dataQuery['searchAmountMax']);
            
            // params
            define("RESULT_PER_PAGE", 15);
            $whereClause = implode(' AND ', $conditions);
            $limit = RESULT_PER_PAGE;
            $currentPage = !empty($dataQuery['currentPage']) ? $dataQuery['currentPage'] : 1;
            $offset = RESULT_PER_PAGE * ($currentPage - 1);

            // SELECT
            $reqSql = "SELECT 
                transaction_id,
                transaction_user_id,
                transaction_amount,
                transaction_type,
                transaction_category,
                transaction_date,
                transaction_note,
                DATE_FORMAT(transaction_date, '%d/%m/%Y') as formatted_date, 
                (
                    SELECT COUNT(*)
                    FROM transaction sub
                    WHERE sub.transaction_category = transaction.transaction_category 
                    AND $whereClause
                ) AS count_categories    
                FROM 
                    transaction
                WHERE 
                    $whereClause
                ORDER BY 
                    $orderByField $orderType
                LIMIT  
                    " .RESULT_PER_PAGE. " OFFSET $offset 
            ";
            $query = $db->prepare($reqSql);

            // bind
            foreach ($binds as $key => $value) {
                $query->bindValue($key, $value);
            }
            $query->execute();
            // list transactions by search
            $listTransactions = $query->fetchAll(PDO::FETCH_ASSOC);

            // count selected transactions
            $countSql = "SELECT COUNT(*) AS countTransactions FROM transaction WHERE $whereClause";
            $countQuery = $db->prepare($countSql);
            foreach ($binds as $key => $value) {
                $countQuery->bindValue($key, $value);
            }
            $countQuery->execute();
            $countTrs = $countQuery->fetch()['countTransactions'];

            // data selected
            $transactionsData = [
                'listTransactions' => $listTransactions,
                'countTransactions' => $countTrs,
                'itemPerPage' => $limit 
            ];
            return $transactionsData;
        }

        private function addConditionQuery(&$conditions, &$binds, $field, $operator, $dataKey, $value, $like = false) {
            if (!empty($value)) {
                $conditions[] = "$field $operator $dataKey";
                $binds[$dataKey] = ($like) ? "%$value%" : $value;
            }
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