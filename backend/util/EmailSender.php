<?php

    namespace App\Mail;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    interface I_Emailsender {
        function sendEmailResetPass($params);
        function sendEmailUpdateEmail($params);
        function sendEmailToSupport($params);
        function setParamServer();
        function getPhpMailer();
    };

    class EmailSender implements I_Emailsender {

        private $phpMailer;


        public function getPhpMailer() {
            if (!$this->phpMailer) $this->phpMailer = new PHPMailer(true);
            return $this->phpMailer;
        }

        public function setParamServer() {
            $this->getPhpMailer()->isSMTP();
            $this->getPhpMailer()->Host = 'smtp-mail.outlook.com'; 
            $this->getPhpMailer()->SMTPAuth = true;
            $this->getPhpMailer()->CharSet = 'UTF-8';
            $this->getPhpMailer()->Username = 'jaywed@hotmail.fr';
            $this->getPhpMailer()->Password = 'embffkupkkppbwuo'; 
            $this->getPhpMailer()->SMTPSecure = 'tls';
            $this->getPhpMailer()->Port = 587;

            //$this->getPhpMailer()->SMTPDebug = 2;
            $this->getPhpMailer()->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }
        // $params['urlToResetPass']
        public function sendEmailResetPass($params) {
            $this->setParamServer();
            $this->getPhpMailer()->setFrom('jaywed@hotmail.fr', 'Ecofast');
            $this->getPhpMailer()->addAddress($params['email']);  

            $this->getPhpMailer()->isHTML(true);
            $this->getPhpMailer()->Subject = 'Réintialisation du mot de passe.';
            $this->getPhpMailer()->Body = "<a href='".$params['urlToResetPass']. "'>".$params['urlToResetPass']."</a>";

            $this->getPhpMailer()->AltBody = 'Un mail a été envoyé pour réinitialiser votre mot de passe, cliquez sur le lien.';                               

            return $this->getPhpMailer()->send();
        }

        public function sendEmailUpdateEmail($params) {
            $this->setParamServer();
            $this->getPhpMailer()->setFrom('jaywed@hotmail.fr', 'Ecofast');
            $this->getPhpMailer()->addAddress($params['email']);  

            $this->getPhpMailer()->isHTML(true);
            $this->getPhpMailer()->Subject = 'Mise à jour de votre email.';
            $this->getPhpMailer()->Body = "Cliquez sur ce lien pour pouvoir mettre à jour votre email.
            
            <a href='".$params['urlToResetPass']. "'>".$params['urlToResetPass']."</a>";

            $this->getPhpMailer()->AltBody = 'Un mail a été envoyé pour réinitialiser votre email, cliquez sur le lien.';                               

            return $this->getPhpMailer()->send();
        }

        public function sendEmailToSupport($params) {
            $this->setParamServer();
            $this->getPhpMailer()->setFrom('jaywed@hotmail.fr', 'Ecofast');
            $this->getPhpMailer()->addAddress('jaywed@hotmail.fr');  
            $this->getPhpMailer()->Subject = "Demande d'assistance de ".$params['firstName']." ".$params['lastName'];
            $safeMessage = htmlspecialchars($params['message'], ENT_QUOTES, 'UTF-8');
            $messageWithBr = nl2br($safeMessage, false);

            $this->getPhpMailer()->isHTML(true);
            $this->getPhpMailer()->Body = "
            <div>
               <h2>Informations de l'utilisateur</h2>
                <ul>
                    <li>Nom: ".$params['firstName']." </li>
                    <li>Prénom: ".$params['lastName']." </li>
                    <li>Email: ".$params['emailSender']." </li>
                </ul>
            </div>
            <h2>Message: </h2>
            <p>$messageWithBr</p>";

            $this->getPhpMailer()->AltBody = "Une demande d'assistance a été envoyée par un utilisateur.";                               

            return $this->getPhpMailer()->send();
        }

    }
?>