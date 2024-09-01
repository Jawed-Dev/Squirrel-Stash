<?php
    interface I_ModelStatisticGetDataYear {
        //get data year
        function getYearListTrsByMonth($db, $data);
        function getTotalTrsByYear($db, $data);
        function getBiggestTrsByYear($db, $data);
        function getBiggestMonthByYear($db, $data);
        function getYearListTrsByCategories($db, $data);
        function getTopYearCategories($db, $data);
    }

    class ModelStatisticGetDataYear implements I_ModelStatisticGetDataYear {
        public function getYearListTrsByMonth($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT month_name AS labels,
                    COALESCE(SUM(transaction_amount), 0) AS total_amount
                FROM (
                    SELECT 'Janvier' AS month_name, 1 AS month UNION ALL
                    SELECT 'Février', 2 UNION ALL
                    SELECT 'Mars', 3 UNION ALL
                    SELECT 'Avril', 4 UNION ALL
                    SELECT 'Mai', 5 UNION ALL
                    SELECT 'Juin', 6 UNION ALL
                    SELECT 'Juillet', 7 UNION ALL
                    SELECT 'Août', 8 UNION ALL
                    SELECT 'Septembre', 9 UNION ALL
                    SELECT 'Octobre', 10 UNION ALL
                    SELECT 'Novembre', 11 UNION ALL
                    SELECT 'Décembre', 12
                ) AS months
                LEFT JOIN transaction ON MONTH(transaction_date) = months.month
                    AND YEAR(transaction_date) = :year
                    AND transaction_type = :trsType
                    AND transaction_user_id = :userId
                GROUP BY months.month_name
                ORDER BY months.month;
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->execute();
            $listTotalTransactions = $query->fetchAll(PDO::FETCH_ASSOC);

            return $listTotalTransactions;
        }

        function getTotalTrsByYear($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT 
                SUM(transaction_amount) AS total_transactions
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date) = :year
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->execute();
            $totalTransactions = $query->fetch(PDO::FETCH_ASSOC);

            return $totalTransactions;
        }

        public function getBiggestTrsByYear($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT 
                transaction_amount,
                transaction_category
                FROM transaction
                WHERE transaction_user_id = :userId
                AND YEAR(transaction_date) = :year
                AND transaction_type = :trsType
                ORDER BY transaction_amount DESC
                LIMIT 1
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->execute();

            $biggestTransaction = $query->fetch(PDO::FETCH_ASSOC);
            return $biggestTransaction;
        }

        function getBiggestMonthByYear($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT month_name AS month,
                COALESCE(SUM(transaction_amount), 0) AS total_amount
            FROM (
                    SELECT 'Janvier' AS month_name, 1 AS month UNION ALL
                    SELECT 'Février', 2 UNION ALL
                    SELECT 'Mars', 3 UNION ALL
                    SELECT 'Avril', 4 UNION ALL
                    SELECT 'Mai', 5 UNION ALL
                    SELECT 'Juin', 6 UNION ALL
                    SELECT 'Juillet', 7 UNION ALL
                    SELECT 'Août', 8 UNION ALL
                    SELECT 'Septembre', 9 UNION ALL
                    SELECT 'Octobre', 10 UNION ALL
                    SELECT 'Novembre', 11 UNION ALL
                    SELECT 'Décembre', 12
                ) AS months
                LEFT JOIN transaction
                    ON MONTH(transaction_date) = months.month
                    AND YEAR(transaction_date) = :year
                    AND transaction_user_id = :userId
                WHERE transaction_amount > 0
                GROUP BY month_name
                ORDER BY total_amount DESC
                LIMIT 1;
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->execute();
            $biggestMonth = $query->fetch(PDO::FETCH_ASSOC);
            return $biggestMonth;
        }

        function getYearListTrsByCategories($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = "SELECT category_name AS labels,
                    COALESCE(SUM(transaction_amount), 0) AS total_amount
                FROM category
                LEFT JOIN transaction 
                    ON category_name = transaction_category  
                    AND transaction_user_id = :userId
                    AND YEAR(transaction_date) = :year
                    AND category_type = transaction_type
                WHERE 
                    category_type = :trsType
                GROUP BY category_name
                ORDER BY category_id;
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->execute();
            $biggestMonth = $query->fetchAll(PDO::FETCH_ASSOC);
            return $biggestMonth;
        }

        function getTopYearCategories($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];
            $reqSql = 
                "SELECT transaction_category AS labels, 
                    COALESCE(SUM(transaction_amount), 0) AS total_amount
                FROM transaction
                WHERE 
                    transaction_user_id = :userId
                    AND YEAR(transaction_date) = :year
                    AND transaction_amount > 0
                    AND transaction_type = :trsType
                GROUP BY transaction_category
                ORDER BY total_amount DESC
                LIMIT 5
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':year',  $dataQuery['selectedYear'], PDO::PARAM_INT);
            $query->bindValue(':trsType',  $dataQuery['transactionType'], PDO::PARAM_STR);
            $query->execute();
            $biggestMonth = $query->fetchAll(PDO::FETCH_ASSOC);
            return $biggestMonth;
        }

        // Date
        public function getLastDayByMonth($year, $month) {
            $date = new DateTime("$year-$month-01");
            $date->modify('last day of this month');
            return $date->format('d');
        }
    }
?>