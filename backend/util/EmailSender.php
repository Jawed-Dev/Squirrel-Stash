<?php

    namespace App\Mail;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    interface I_Emailsender {
        function sendEmailResetPass($params);
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
            $this->getPhpMailer()->Password = 'opylszgarumwbfwc'; 
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
            $this->getPhpMailer()->Body = "<a href='".$params['urlToResetPass']. "'>".$params['urlToResetPass'].".</a>";

            $this->getPhpMailer()->AltBody = 'Un mail a été envoyé pour réinitialiser votre mot de passe.';                               

            return $this->getPhpMailer()->send();
        }

    }
?>