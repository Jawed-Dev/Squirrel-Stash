<?php
    class RouterPage {
        public function hanglePageRouter() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if(!empty($_GET['page'])) {
                    switch($_GET['page']) {
                        case 'pageIndex': {
                            getControllerMain()->authorizePage();
                            break;
                        }
                        case 'pageLogin': {
                            getControllerMain()->getControllerUser()->authorizePage();
                            break;
                        }
                        case 'pageRegister': {
                            getControllerMain()->getControllerUser()->authorizePage();
                            break;
                        }
                        case 'updateEmail': {
                            getControllerMain()->getControllerUser()->authorizePage();
                            break;
                        }
                        case 'pageForgotPass': {
                            getControllerMain()->getControllerUser()->authorizePage();
                            break;
                        }
                        case 'pageUser': {
                            getControllerMain()->getControllerUser()->authorizePage();
                            break;
                        }
                        case 'pageAccount': {
                            getControllerMain()->getControllerUser()->authorizePage();
                            break;
                        }
                        case 'pageResetPass': {
                            getControllerMain()->getControllerUser()->authorizePage();
                            break;
                        }
                        case 'pageTransactions': {
                            getControllerMain()->getControllerStatisticAction()->authorizePage();
                            break;
                        }
                        case 'pageDashboard': {
                            getControllerMain()->getControllerStatisticAction()->authorizePage();
                            break;
                        }
                        case 'pageSeeMoreMobile': {
                            getControllerMain()->getControllerStatisticAction()->authorizePage();
                            break;
                        }
                        case 'pageAnnualSummary': {
                            getControllerMain()->getControllerStatisticAction()->authorizePage();
                            break;
                        }
        
                        default: {
                            http_response_code(404);
                            getControllerMain()->sendJsonResponse(['message' => 'Page not found']);
                            break;
                        }
                    }
                }
            }
        }
    }
?>