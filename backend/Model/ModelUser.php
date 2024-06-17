<?php 

    interface I_ModelUser {
        function isUserExistById($db, $userId);
        function getUserIdByLogin($db, $data);
    }

    class ModelUser implements I_ModelUser {
        
        public function __construct() {
            
        }


        public function isUserExistById($db, $userId) {
            $reqSql = "SELECT 1 FROM user WHERE id = :id";
            $query = $db->prepare($reqSql);
            $query->bindValue(':id',  $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
            return ($result) ? true : false;
        }

        public function getUserIdByLogin($db, $data) {
            $dataPost = json_decode($data, true);

            $reqSql = "SELECT id FROM user WHERE email = :email AND password = :password";
            $query = $db->prepare($reqSql);
            $query->bindValue(':email', $dataPost['email']);
            $query->bindValue(':password', $dataPost['password']);
            $query->execute();
            $userInfo = $query->fetch(PDO::FETCH_ASSOC);

            return (!empty($userInfo['id'])) ? $userInfo['id'] : null;
        }
    }
?>