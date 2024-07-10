<?php 

    interface I_ModelUser {
        // get
        function isUserExistFromId($db, $userId);
        function getUserIdIfValidLogin($db, $data);
        function isValidResetPassToken($db, $data);
        function getUserDataByLoginData($db, $data);
        // action
        function insertUser($db, $data);
        function insertResetPassToken($db, $data);
        function updatePassword($db, $data);
        function insertUniqueTokenResetPass($db, $uniqueToken);
        function deleteResetPassTokenByEmail($db, $data);

        function getUserFirstName($db, $userId);
        function getDataUserProfil($db, $userId);
        function updateDataUserProfil($db, $data);
    }

    class ModelUser implements I_ModelUser {

        public function insertUser($db, $data) {
            $dataQuery = $data;
            $reqSql = 
            "INSERT INTO user (user_last_name, user_first_name, user_email, user_password, user_last_ip, user_last_connexion)
            SELECT :lastName, :firstName, :email, :password, :lastIp, NOW()
            FROM DUAL
            WHERE NOT EXISTS (
                SELECT 1 from user WHERE user_email = :email
            )";

            $hashedPassword = password_hash($dataQuery['password'], PASSWORD_BCRYPT);
            $ip = ''; 

            $query = $db->prepare($reqSql);
            $query->bindValue(':lastName', $dataQuery['lastName'], PDO::PARAM_STR);
            $query->bindValue(':firstName',  $dataQuery['firstName'], PDO::PARAM_STR);
            $query->bindValue(':email',  $dataQuery['email'], PDO::PARAM_STR);
            $query->bindValue(':password',  $hashedPassword, PDO::PARAM_STR);
            $query->bindValue(':lastIp',  $ip , PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }
        
        public function isUserExistFromId($db, $userId) {
            $reqSql = "SELECT 1 FROM user WHERE user_id = :id";
            $query = $db->prepare($reqSql);
            $query->bindValue(':id',  $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $isSuccessRequest = $result !== false;
            return $isSuccessRequest;
        }

        public function getDataUserProfil($db, $userId) {
            $reqSql = 
            "SELECT 
                user_first_name,
                user_last_name,
                user_birthday,
                user_gender,
                user_role_level
            FROM 
                user 
            WHERE 
                user_id = :id";
            $query = $db->prepare($reqSql);
            $query->bindValue(':id',  $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return ($result) ? $result : null;
        }

        public function updateDataUserProfil($db, $data) {
            $dataQuery = $data['bodyData'];
            $userId = $data['userId'];
            $reqSql = 
            "UPDATE 
                user 
            SET 
                user_first_name = :firstName,
                user_last_name = :lastName,
                user_birthday = :birthday,
                user_gender = :gender
            WHERE 
                user_id = :userId";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId',  $userId, PDO::PARAM_INT);
            $query->bindValue(':firstName',  $dataQuery['firstName'], PDO::PARAM_STR);
            $query->bindValue(':lastName',  $dataQuery['lastName'], PDO::PARAM_STR);
            $query->bindValue(':birthday',  $dataQuery['birthday'], PDO::PARAM_STR);
            $query->bindValue(':gender',  $dataQuery['gender'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function getUserDataByLoginData($db, $data) {
            $reqSql = "SELECT user_id, user_password FROM user WHERE user_email = :email";
            $query = $db->prepare($reqSql);
            $query->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return ($result) ? $result : null;
        }

        public function getUserIdIfValidLogin($db, $data) {
            $userInfo = $this->getUserDataByLoginData($db, $data);
            if(empty($data['password'])) return null;

            $userHashedPassword = !empty($userInfo['user_password']) ? $userInfo['user_password'] : null;
            $isCorrectPassword =  password_verify($data['password'], $userHashedPassword);
            $isValidUserId = !empty($userInfo['user_id']) ? $userInfo['user_id'] : null;
            $isLoginSuccess = $isValidUserId && $isCorrectPassword;

            return ($isLoginSuccess) ? $userInfo['user_id'] : null;
        }

        public function getUserFirstName($db, $userId) {
            //var_dump($db, $userId);
            $reqSql = "SELECT user_first_name FROM user WHERE user_id = :userId";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId', $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return ($result) ? $result : null;
        }

        public function updatePassword($db, $data) {
            $reqSql = "UPDATE user 
            INNER JOIN reset_password ON user_id = reset_pass_userId 
            SET user_password = :newPass, user_last_update_password = NOW()
            WHERE reset_pass_token = :resetPassToken
            AND reset_pass_expireAt > NOW() 
            AND reset_pass_createdAt < NOW() 
            ";

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = $db->prepare($reqSql);
            $query->bindValue(':newPass', $hashedPassword, PDO::PARAM_STR);
            $query->bindValue(':resetPassToken', $data['resetPassToken'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount();
            return ($isSuccessRequest) ? true : false;
        }

        public function insertUniqueTokenResetPass($db, $uniqueToken) {
            $tokenExist = true;
            $tokenResetPass = '';
            while ($tokenExist) {
                $tokenResetPass = $uniqueToken;
                $query = $db->prepare("SELECT 1 AS count FROM reset_password WHERE reset_pass_token = :resetPassToken");
                $query->bindValue(':resetPassToken', $tokenResetPass, PDO::PARAM_STR);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                $tokenExist = $result !== false;
            }
            return $tokenResetPass;
        }

        public function isValidResetPassToken($db, $data) {
            $reqSql = "SELECT 1 FROM user 
            INNER JOIN reset_password ON user_id = reset_pass_userId
            WHERE reset_pass_token = :resetPassToken
            AND reset_pass_expireAt > NOW() 
            AND reset_pass_createdAt < NOW()";
            
            $query = $db->prepare($reqSql);
            $query->bindValue(':resetPassToken', $data['resetPassToken'], PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $isValidToken = $result !== false;
            return $isValidToken;
        }

        public function deleteResetPassTokenByEmail($db, $data) {
            $reqSql = "DELETE FROM reset_password
            WHERE reset_pass_email = :email
            LIMIT 1
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function deleteResetPassTokenByToken($db, $data) {
            $reqSql = "DELETE FROM reset_password
            WHERE reset_pass_token = :resetPassToken
            LIMIT 1
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':resetPassToken', $data['resetPassToken'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function insertResetPassToken($db, $data) {
            $dataQuery = $data['bodyData'];
            $reqSql = "INSERT INTO reset_password (reset_pass_token, reset_pass_createdAt, reset_pass_expireAt, reset_pass_userId, reset_pass_email, reset_pass_ip_sender)
            SELECT :resetPassToken, NOW(), NOW() + INTERVAL 10 MINUTE, user_id, :email, :ipSender
            FROM user 
            WHERE user_email = :email
            AND NOT EXISTS (
                SELECT 1
                FROM reset_password AS rp
                WHERE rp.reset_pass_email = :email
            )
            ";
            
            $query = $db->prepare($reqSql);
            $ipUser = '';

            $query->bindValue(':resetPassToken', $dataQuery['resetPassToken'], PDO::PARAM_STR);
            $query->bindValue(':userId', 1, PDO::PARAM_INT);
            $query->bindValue(':email', $dataQuery['email'], PDO::PARAM_STR);
            $query->bindValue(':ipSender', $ipUser, PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount();
            return ($isSuccessRequest) ? true : false;
        }

    }
?>