<?php
    class RouterAction {
        public function handleRouterAction() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                if(!empty($_GET['actionData'])) {
                    switch($_GET['actionData']) {
                        case 'createAccount': {
                            getControllerMain()->getControllerUser()->addNewUser();
                            break;
                        }
                        case 'saveThreshold': {
                            getControllerMain()->getControllerStatisticAction()->saveThreshold();
                            break;
                        }
                        case 'addTransaction' : {
                            getControllerMain()->getControllerStatisticAction()->addTransaction();
                            break;
                        }
                        case 'deleteTransaction' : {
                            getControllerMain()->getControllerStatisticAction()->deleteTransaction();
                            break;
                        }
                        case 'updateTransaction' : {
                            getControllerMain()->getControllerStatisticAction()->updateTransaction();
                            break;
                        }
                        case 'sendResetPass' : {
                            getControllerMain()->getControllerUser()->sendResetPassToken();
                            break;
                        }
                        case 'sendUpdateMail' : {
                            getControllerMain()->getControllerUser()->sendUpdateMail();
                            break;
                        }
                        case 'updatePasswordByToken' : {
                            getControllerMain()->getControllerUser()->updatePasswordByToken();
                            break;
                        }
                        case 'updatePasswordByUserId' : {
                            getControllerMain()->getControllerUser()->updatePasswordByUserId();
                            break;
                        }
                        
                        case 'updateEmail' : {
                            getControllerMain()->getControllerUser()->updateEmail();
                            break;
                        }
                        case 'updateUserProfil' : {
                            getControllerMain()->getControllerUser()->updateUserProfil();
                            break;
                        }
    
                        case 'sendEmailToSupport' : {
                            getControllerMain()->getControllerUser()->sendEmailToSupport();
                            break;
                        }
                    }
                }
            }
        }
    }
?>