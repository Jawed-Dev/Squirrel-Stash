// lazy loading
const PageLogin = () => import('@/page/PageLogin.vue');
const PageCreateAcc = () => import('@/page/PageCreateAcc.vue');
const PageDashboard = () => import('@/page/PageDashboard.vue');
const PageHistoryTransactions = () => import('@/page/PageHistoryTransactions.vue');
const PageTemporary = () => import('@/page/PageTemporary.vue');
const PageForgotPass = () => import('@/page/PageForgotPass.vue');
const PageResetPass = () => import('@/page/PageResetPass.vue');
const PageAccount = () => import('@/page/PageAccount.vue');
const PageUser = () => import('@/page/PageUser.vue');
const PageAnnualSummary = () => import('@/page/PageAnnualSummary.vue');
const Page404 = () => import('@/page/Page404.vue');


import  useConfigFetchGetPage from "@/composable/useConfigFetchGetPage";
import { createRouter, createWebHistory } from 'vue-router';
import { getLStorageAuthToken } from "@/composable/useLocalStorage";
import { isValidResetPassToken  } from "@/composable/useBackendGetData";
import { updateEmail } from '@/composable/useBackendActionData';
import { createToast } from '@/composable/useToastNotification';

const routes = [
  { path: '/', component: PageTemporary, meta: { page:'Squirrel Stash', request: 'pageIndex'}},
  { path: '/connexion', component: PageLogin, meta: { page:'Connexion', request: 'pageLogin'}},
  { path: '/inscription', component: PageCreateAcc, meta: { page:'Inscription', request: 'pageRegister' }},
  { path: '/tableau-de-bord', component: PageDashboard, meta: { page:'Tableau de bord', request: 'pageDashboard' }},
  { path: '/mot-de-passe-oublie', component: PageForgotPass, meta: { page:'Mot de passe oublié', request: 'pageForgotPass' }},
  { path: '/reinitialiser-mot-de-passe', component: PageResetPass, meta: { page:'Réinitialiser mot de passe', request: 'pageResetPass' }},
  { path: '/historique-transactions', component: PageHistoryTransactions, meta: { page:'Historique', request: 'pageTransactions' }},
  { path: '/mon-compte', component: PageAccount, meta: { page:'Mon compte', request: 'pageAccount' }},
  { path: '/reinitialiser-email', component: PageTemporary, meta: { page:'Réinitialiser email', request: 'updateEmail' }},
  { path: '/utilisateur', component: PageUser, meta: { page:'Utilisateur', request: 'pageUser' }},
  { path: '/recap-annuel', component: PageAnnualSummary, meta: { page:'Récapitulatif annuel', request: 'pageAnnualSummary' }},
  { path: '/404', component: Page404, meta: { page:'Page introuvable', request: 'page404' }},
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});

router.beforeEach(async (to, from, next) => {
    const currentRequest = to.meta.request;
    // redirect not allowed pages  
    if(!currentRequest) {
      next('/404');
      return;
    } 
    // for dont request the back when we got page 404
    if(currentRequest === 'page404') {
      next();
      return;
    }

    // token JWT
    let localToken = getLStorageAuthToken();
    const dataPage = await useConfigFetchGetPage(currentRequest, localToken);
    const isSessionActive = dataPage?.isSessionActive;

    document.title = to.meta.page;
    
    switch(currentRequest) {
      case 'pageIndex' : {
        (isSessionActive) ? next('/tableau-de-bord') : next('/connexion');
        break;
      }
      case 'pageLogin' : {
        (isSessionActive) ? next('/tableau-de-bord') : next();
        break;
      }
      case 'pageRegister' : {
        (isSessionActive) ? next('/tableau-de-bord') : next();
        break;
      }
      case 'pageForgotPass' : {
        (isSessionActive) ? next('/tableau-de-bord') : next();
        break;
      }
      case 'updateEmail' : {
        const token = to.query.token;
        const response = await updateEmail(token);
        next('/mon-compte');
        if(response?.isSuccessRequest) createToast('Votre email a été modifié avec succès.', 'success');
        else createToast("Une erreur est survenue, \nVotre email n'a pas été modifié.", 'error');
        break;
      }
      case 'pageResetPass' : {
        const isParamUrlValid = to.query.token;
        const isValidToken = await isValidResetPassToken(to.query.token);
        if(!isParamUrlValid || !isValidToken) next('/connexion'); 
        (isSessionActive) ? next('/tableau-de-bord') : next();
        break;
      }
      case 'pageAccount' : {
        (isSessionActive) ? next() : next('/connexion');
        break;
      }
      case 'pageUser' : {
        (isSessionActive) ? next() : next('/connexion');
        break;
      }
      case 'pageDashboard' : {
        (isSessionActive) ? next() : next('/connexion');
        break;
      }
      case 'pageTransactions' : {
        (isSessionActive) ? next() : next('/connexion');
        break;
      }
      case 'pageAnnualSummary' : {
        (isSessionActive) ? next() : next('/connexion');
        break;
      }
      
      default : {
          next();
          break;
      }
    }    
});




export default router;