<?php 

    interface I_ModelUser {
        // get
        function isUserExistFromId($db, $userId);
        function getUserIdIfValidLogin($db, $data);
        function isValidResetPassToken($db, $data);
        function getUserLogDataByEmail($db, $data);
        function getCurrentEmail($db, $userId);
        function getUserPass($db, $data);

        // action
        function insertUser($db, $data);
        function insertResetPassToken($db, $data);
        function updatePasswordByToken($db, $data);
        function updatePasswordByUserId($db, $userId);
        function insertUniqueTokenResetPass($db);
        function deleteResetPassToken($db, $data);
        function insertUniqueTokenUpdateEmail($db);
        function deleteUpdateEmailToken($db, $data);

        function getUserFirstName($db, $userId);
        function getDataUserProfil($db, $userId);
        function updateUserProfil($db, $data);
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

        public function getCurrentEmail($db, $userId) {
            $reqSql = "SELECT user_email FROM user WHERE user_id = :id";
            $query = $db->prepare($reqSql);
            $query->bindValue(':id',  $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return ($result['user_email']) ? $result['user_email'] : null;
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

        public function updateUserProfil($db, $data) {
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

        public function getUserLogDataByEmail($db, $data) {
            $reqSql = "SELECT user_id, user_password FROM user WHERE user_email = :email";
            $query = $db->prepare($reqSql);
            $query->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return ($result) ? $result : null;
        }

        public function getUserPass($db, $userId) {
            $reqSql = "SELECT user_password FROM user WHERE user_id = :userId";
            $query = $db->prepare($reqSql);
            $query->bindValue(':userId', $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return ($result['user_password']) ? $result['user_password'] : null;
        }

        public function getUserIdIfValidLogin($db, $data) {
            $userInfo = $this->getUserLogDataByEmail($db, $data);
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

        public function updatePasswordByToken($db, $data) {
            $reqSql = 
            "UPDATE user 
            INNER JOIN update_password ON user_id = update_pass_user_id 
            SET user_password = :newPass, user_last_update_password = NOW()
            WHERE update_pass_token = :resetPassToken
            AND update_pass_expireAt > NOW() 
            AND update_pass_createdAt <= NOW()";

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = $db->prepare($reqSql);
            $query->bindValue(':newPass', $hashedPassword, PDO::PARAM_STR);
            $query->bindValue(':resetPassToken', $data['resetPassToken'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function updatePasswordByUserId($db, $data) {
            $dataQuery = $data['bodyData'];
            $userId = $data['userId'];

            $currentPassDb = $this->getUserPass($db, $userId);

            $reqSql = 
            "UPDATE user 
            SET user_password = :newPass, user_last_update_password = NOW()
            WHERE 
                user_id = :userId";
            
            //$hashedCurrentPass = password_hash($dataQuery['oldPass'], PASSWORD_BCRYPT);
            $hashedNewPass = password_hash($dataQuery['newPass'], PASSWORD_BCRYPT);

            $isCorrectPassword = password_verify($dataQuery['oldPass'], $currentPassDb);

            $query = $db->prepare($reqSql);
            $query->bindValue(':newPass', $hashedNewPass, PDO::PARAM_STR);
            $query->bindValue(':userId', $userId, PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0 && $isCorrectPassword;
            return $isSuccessRequest;
        }

        public function updateEmail($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = "UPDATE user 
            INNER JOIN update_email ON user_id = update_email_user_id  
            SET user_email = update_email_new_email, user_last_update_email = NOW()
            WHERE update_email_token = :token
            AND user_id = :userId
            AND update_email_expireAt > NOW() 
            AND update_email_createdAt <= NOW() 
            ";

            $query = $db->prepare($reqSql);
            $query->bindValue(':userId', $userId, PDO::PARAM_INT);
            $query->bindValue(':token', $dataQuery['token'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function createRandomHashSha256() {
            $randomBytes = random_bytes(32); 
            return hash('sha256', $randomBytes); 
        }

        public function insertUniqueTokenResetPass($db) {
            $isTokenExist = true;
            $token = '';
            while ($isTokenExist) {
                $token = $this->createRandomHashSha256();
                $query = $db->prepare("SELECT 1 AS count FROM update_password WHERE update_pass_token = :resetPassToken");
                $query->bindValue(':resetPassToken', $token, PDO::PARAM_STR);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                $isTokenExist = $result !== false;
            }
            return $token;
        }

        public function insertUniqueTokenUpdateEmail($db) {
            $isTokenExist = true;
            $token = '';
            while ($isTokenExist) {
                $token = $this->createRandomHashSha256();
                $query = $db->prepare("SELECT 1 AS count FROM update_email WHERE update_email_token = :updateEmailToken");
                $query->bindValue(':updateEmailToken', $token, PDO::PARAM_STR);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                $isTokenExist = $result !== false;
            }
            return $token;
        }

        public function isValidResetPassToken($db, $data) {
            $reqSql = "SELECT 1 FROM user 
            INNER JOIN update_password ON user_id = update_pass_user_id
            WHERE update_pass_token = :resetPassToken
            AND update_pass_expireAt > NOW() 
            AND update_pass_createdAt < NOW()";
            
            $query = $db->prepare($reqSql);
            $query->bindValue(':resetPassToken', $data['resetPassToken'], PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $isValidToken = $result !== false;
            return $isValidToken;
        }

        public function deleteResetPassToken($db, $data) {
            
            $reqSql = "DELETE FROM update_password
            WHERE update_pass_email = :email
            ";
            $query = $db->prepare($reqSql);
            $query->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function deleteUpdateEmailToken($db, $data) {
            $userId = $data['userId'];
            $dataQuery = $data['bodyData'];

            $reqSql = "DELETE FROM update_email
            WHERE update_email_user_id = :userId
            ";
            $query = $db->prepare($reqSql);
            //$query->bindValue(':token', $dataQuery['token'], PDO::PARAM_STR);
            $query->bindValue(':userId', $userId, PDO::PARAM_INT);
            $query->execute();
            $isSuccessRequest = $query->rowCount() > 0;
            return $isSuccessRequest;
        }

        public function deleteResetPassTokenByToken($db, $data) {
            $reqSql = "DELETE FROM update_password
            WHERE update_pass_token = :resetPassToken
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
            $userId = $data['userId'];

            $reqSql = "INSERT INTO update_password (update_pass_token, update_pass_createdAt, update_pass_expireAt, update_pass_user_id, update_pass_email, update_pass_ip_sender)
            SELECT :resetPassToken, NOW(), NOW() + INTERVAL 10 MINUTE, user_id, :email, :ipSender
            FROM user 
            WHERE user_email = :email
            AND NOT EXISTS (
                SELECT 1
                FROM update_password AS rp
                WHERE rp.update_pass_email = :email
            )
            ";
            
            $query = $db->prepare($reqSql);
            $ipUser = '';

            $query->bindValue(':resetPassToken', $dataQuery['resetPassToken'], PDO::PARAM_STR);
            $query->bindValue(':userId', $userId, PDO::PARAM_INT);
            $query->bindValue(':email', $dataQuery['email'], PDO::PARAM_STR);
            $query->bindValue(':ipSender', $ipUser, PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount();
            return ($isSuccessRequest) ? true : false;
        }

        public function insertUpdateMailToken($db, $data) {
            $dataQuery = $data['bodyData'];
            $userId = $data['userId'];

            $reqSql = "INSERT INTO update_email (update_email_token, update_email_createdAt, update_email_expireAt, update_email_user_id, update_email_new_email, update_email_ip_sender)
            SELECT :token, NOW(), NOW() + INTERVAL 10 MINUTE, user_id, :newEmail, :ipSender
            FROM user 
            WHERE user_email = :currentEmail
            AND NOT EXISTS (
                SELECT 1
                FROM update_email AS ue
                WHERE ue.update_email_new_email = :newEmail
            )
            ";
            
            $query = $db->prepare($reqSql);
            $ipUser = '';

            $query->bindValue(':token', $dataQuery['token'], PDO::PARAM_STR);
            $query->bindValue(':userId', $userId, PDO::PARAM_INT);
            $query->bindValue(':newEmail', $dataQuery['newEmail'], PDO::PARAM_STR);
            $query->bindValue(':currentEmail', $dataQuery['currentEmail'], PDO::PARAM_STR);
            $query->bindValue(':ipSender', $ipUser, PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount();
            return ($isSuccessRequest) ? true : false;
        }

    }
?>