<?php 

    interface I_ModelUser {
        function isUserExistFromId($db, $userId);
        function getUserIdFromLogin($db, $data);
        function insertUser($db, $data);
        function insertResetPassToken($db, $data);
        function updatePassword($db, $data);
        function getUniqueTokenResetPass($db);
        function deleteResetPassTokenByEmail($db, $data);
        function createRandomHashSha();
        function isValidResetPassToken($db, $data);
    }

    class ModelUser implements I_ModelUser {

        public function insertUser($db, $data) {
            $dataQuery = $data;
            $reqSql = 
            "INSERT INTO user (user_last_name, user_first_name, user_email, user_password, user_last_ip, user_last_connexion)
            SELECT :lastName, :firstName, :email, :password, :lastIp, :lastConnexion
            FROM DUAL
            WHERE NOT EXISTS (
            SELECT 1 from user WHERE user_email = :email
            )";

            $hashedPassword = password_hash($dataQuery['password'], PASSWORD_BCRYPT);
            $timeNow = date("Y-m-d H:i:s");
            //$ip = $_SERVER['REMOTE_ADDR']; 

            $query = $db->prepare($reqSql);
            $query->bindValue(':lastName', $dataQuery['lastName'], PDO::PARAM_STR);
            $query->bindValue(':firstName',  $dataQuery['firstName'], PDO::PARAM_STR);
            $query->bindValue(':email',  $dataQuery['email'], PDO::PARAM_STR);
            $query->bindValue(':password',  $hashedPassword, PDO::PARAM_STR);
            $query->bindValue(':lastIp',  '' , PDO::PARAM_STR);
            $query->bindValue(':lastConnexion',  $timeNow, PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount();
            return ($isSuccessRequest) ? true : false;
        }
        
        public function isUserExistFromId($db, $userId) {
            $reqSql = "SELECT 1 FROM user WHERE user_id = :id";
            $query = $db->prepare($reqSql);
            $query->bindValue(':id',  $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
            return ($result) ? true : false;
        }

        public function getUserIdFromLogin($db, $data) {
            $reqSql = "SELECT user_id, user_password FROM user WHERE user_email = :email";
            $query = $db->prepare($reqSql);
            $query->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $query->execute();
            $userInfo = $query->fetch(PDO::FETCH_ASSOC);

            if(!$userInfo) return null;
            if(empty($data['password'])) return null;

            $userHashedPassword = !empty($userInfo['user_password']) ? $userInfo['user_password'] : null;
            $isCorrectPassword =  password_verify($data['password'], $userHashedPassword);
            $isValidUserId = !empty($userInfo['user_id']) ? $userInfo['user_id'] : null;
            $isLoginSuccess = $isValidUserId && $isCorrectPassword;

            return ($isLoginSuccess) ? $userInfo['user_id'] : null;
        }

        public function updatePassword($db, $data) {
            $reqSql = "UPDATE user 
            INNER JOIN reset_password ON user_id = reset_pass_userId 
            SET user_password = :newPass, user_last_update_password = NOW()
            WHERE reset_pass_token = :resetPassToken
            AND reset_pass_expireAt > NOW() 
            AND reset_pass_createdAt < NOW() 
            ";

            $hashedPassword = password_hash($data['newPassword'], PASSWORD_BCRYPT);
            $query = $db->prepare($reqSql);
            $query->bindValue(':newPass', $hashedPassword, PDO::PARAM_STR);
            $query->bindValue(':resetPassToken', $data['resetPassToken'], PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount();
            return ($isSuccessRequest) ? true : false;
        }

        public function createRandomHashSha() {
            $randomBytes = random_bytes(32); 
            return hash('sha256', $randomBytes); 
        }

        public function getUniqueTokenResetPass($db) {
            $tokenExist = true;
            $tokenResetPass = '';
            while ($tokenExist) {
                $tokenResetPass = $this->createRandomHashSha();
                
                // Vérifier l'unicité du token dans la base de données
                $query = $db->prepare("SELECT COUNT(*) AS count FROM reset_password WHERE reset_pass_token = :resetPassToken");
                $query->bindValue(':resetPassToken', $tokenResetPass, PDO::PARAM_STR);
                $query->execute();
                $countSameToken = $query->fetch(PDO::FETCH_ASSOC);
                $tokenExist = $countSameToken['count'] > 0;
            }
            return $tokenResetPass;
        }

        public function isValidResetPassToken($db, $data) {
            $reqSql = "SELECT 1 FROM user 
            INNER JOIN reset_password ON user_id = reset_pass_userId
            WHERE reset_pass_token = :resetPassToken
            AND reset_pass_expireAt > NOW() 
            AND reset_pass_createdAt < NOW() 
            ";
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
            SELECT :resetPassToken, :createdAt, NOW() + INTERVAL 10 MINUTE, user_id, :email, :ipSender
            FROM user 
            WHERE user_email = :email
            AND NOT EXISTS (
                SELECT 1
                FROM reset_password AS rp
                WHERE rp.reset_pass_email = :email
            )
            ";
            
            $query = $db->prepare($reqSql);

            $timeNow = date("Y-m-d H:i:s");
            // $expireTime = date("Y-m-d H:i:s");
            // $userId = $data['userId'];
            $ipUser = '';

            $query->bindValue(':resetPassToken', $dataQuery['resetPassToken'], PDO::PARAM_STR);
            $query->bindValue(':createdAt', $timeNow, PDO::PARAM_STR);
            //$query->bindValue(':expireAt', $expireTime, PDO::PARAM_STR);
            $query->bindValue(':userId', 1, PDO::PARAM_INT);
            $query->bindValue(':email', $dataQuery['email'], PDO::PARAM_STR);
            $query->bindValue(':ipSender', $ipUser, PDO::PARAM_STR);
            $query->execute();
            $isSuccessRequest = $query->rowCount();
            return ($isSuccessRequest) ? true : false;
        }

    }
?>