<?php
    class RouterAction {
        public function handleRouterAction() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                if(!empty($_GET['actionData'])) {
                    switch($_GET['actionData']) {
                        case 'createAccount': {
                            getControllerMain()->getControllerUser()->fetchInsertUser();
                            break;
                        }
                        case 'saveThreshold': {
                            getControllerMain()->getControllerStatisticAction()->fetchSaveThreshold();
                            break;
                        }
                        case 'addTransaction' : {
                            getControllerMain()->getControllerStatisticAction()->addTransaction();
                            break;
                        }
                        case 'deleteTransaction' : {
                            getControllerMain()->getControllerStatisticAction()->fetchDeleteTransaction();
                            break;
                        }
                        case 'updateTransaction' : {
                            getControllerMain()->getControllerStatisticAction()->fetchUpdateTransaction();
                            break;
                        }
                        case 'sendResetPass' : {
                            getControllerMain()->getControllerUser()->FetchSendResetPassToken();
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