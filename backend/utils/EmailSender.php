<?php

    namespace App\Mail;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    //require_once './key.php';

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
            $this->getPhpMailer()->Password = $_ENV['SMTP_KEY']; 
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
            $this->getPhpMailer()->setFrom('jaywed@hotmail.fr', 'Squirrel Stash');
            $this->getPhpMailer()->addAddress($params['email']);  

            $this->getPhpMailer()->isHTML(true);
            $this->getPhpMailer()->Subject = 'Réintialisation du mot de passe.';
            $this->getPhpMailer()->Body = "<a href='".$params['urlToResetPass']. "'>".$params['urlToResetPass']."</a>";

            $this->getPhpMailer()->AltBody = 'Un mail a été envoyé pour réinitialiser votre mot de passe, cliquez sur le lien.';                               

            return $this->getPhpMailer()->send();
        }

        public function sendEmailUpdateEmail($params) {
            $this->setParamServer();
            $this->getPhpMailer()->setFrom('jaywed@hotmail.fr', 'Squirrel Stash');
            $this->getPhpMailer()->addAddress($params['email']);  

            $this->getPhpMailer()->isHTML(true);
            $this->getPhpMailer()->Subject = 'Mise à jour de votre email.';
            $this->getPhpMailer()->Body = "
                <p>Bonjour,</p>
                
                <p>Vous avez demandé à mettre à jour votre adresse e-mail associée à votre compte Squirrel Stash. Pour compléter cette procédure, veuillez cliquer sur le lien ci-dessous :</p>
                
                <p><a href='".$params['urlToResetPass']."' style='color: #007bff;'>Mettre à jour mon adresse e-mail</a></p>
                
                <p>Si vous n'avez pas fait cette demande, vous pouvez ignorer cet e-mail. Aucune modification ne sera effectuée sur votre compte.</p>
                
                <p>Merci d'utiliser Squirrel Stash pour gérer et protéger vos informations.</p>
                
                <p>Cordialement,</p>
                <p>L'équipe Squirrel Stash</p>
            ";

            $this->getPhpMailer()->AltBody = 'Un mail a été envoyé pour réinitialiser votre email, cliquez sur le lien.';                               

            return $this->getPhpMailer()->send();
        }

        public function sendEmailToSupport($params) {
            $this->setParamServer();
            $this->getPhpMailer()->setFrom('jaywed@hotmail.fr', 'Squirrel Stash');
            $this->getPhpMailer()->addAddress('jaywed@hotmail.fr');  
            $this->getPhpMailer()->Subject = "Demande d'assistance de ".$params['firstName']." ".$params['lastName'];
            $safeMessage = htmlspecialchars($params['message'], ENT_QUOTES, 'UTF-8');
            $messageWithBr = nl2br($safeMessage, false);

            $this->getPhpMailer()->isHTML(true);
            $this->getPhpMailer()->Body = "
            <div style='font-family: Arial, sans-serif;'>
                <h2 style='color: #333;'>Demande d'assistance</h2>
                <p>Un utilisateur a soumis une demande d'assistance avec les détails suivants :</p>
                <h3 style='color: #007bff;'>Informations de l'utilisateur :</h3>
                <ul style='list-style-type: none; padding-left: 0;'>
                    <li><strong>Nom :</strong> " . htmlspecialchars($params['lastName'], ENT_QUOTES, 'UTF-8') . "</li>
                    <li><strong>Prénom :</strong> " . htmlspecialchars($params['firstName'], ENT_QUOTES, 'UTF-8') . "</li>
                    <li><strong>Email :</strong> " . htmlspecialchars($params['emailSender'], ENT_QUOTES, 'UTF-8') . "</li>
                </ul>
                <h3 style='color: #007bff;'>Message :</h3>
                <div style='background-color: #f9f9f9; padding: 10px; border: 1px solid #ddd;'>
                    <p style='white-space: pre-wrap;'>$messageWithBr</p>
                </div>
            </div>";

            $this->getPhpMailer()->AltBody = "Une demande d'assistance a été envoyée par un utilisateur.";                               

            return $this->getPhpMailer()->send();
        }

    }
?>