import { createRouter, createWebHistory } from 'vue-router';
import { ref } from 'vue';

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

import  useConfigFetchGetPage from "@/composable/useConfigFetchGetPage";

import { getLStorageAuthToken, setLStorageAuthToken } from "@/composable/useLocalStorage";
import { isValidResetPassToken, getNewAccessToken  } from "@/composable/useBackendGetData";
import { updateEmail } from './composable/useBackendActionData';

const routes = [
  { path: '/', component: PageTemporary, meta: { page: 'pageIndex'}},
  { path: '/connexion', component: PageLogin, meta: { page: 'pageLogin'}},
  { path: '/inscription', component: PageCreateAcc, meta: { page: 'pageRegister' }},
  { path: '/tableau-de-bord', component: PageDashboard, meta: { page: 'pageDashboard' }},
  { path: '/mot-de-passe-oublie', component: PageForgotPass, meta: { page: 'pageForgotPass' }},
  { path: '/reinitialiser-mot-de-passe', component: PageResetPass, meta: { page: 'pageResetPass' }},
  { path: '/historique-transactions', component: PageHistoryTransactions, meta: { page: 'pageTransactions' }},
  { path: '/mon-compte', component: PageAccount, meta: { page: 'pageAccount' }},
  { path: '/reinitialiser-email', component: PageTemporary, meta: { page: 'updateEmail' }},
  { path: '/utilisateur', component: PageUser, meta: { page: 'pageUser' }},
  { path: '/recap-annuel', component: PageAnnualSummary, meta: { page: 'pageAnnualSummary' }},
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});

router.beforeEach(async (to, from, next) => {
    const currentPage = to.meta.page;  
    if(!currentPage) {
      // 404 ?
      next('/connexion');
      return;
    } 
    let localToken = getLStorageAuthToken();
    if(!localToken) {
      console.log('test1');
    }

    const dataPage = await useConfigFetchGetPage(currentPage, localToken);
    console.log(dataPage);
    const isSessionActive = dataPage?.isSessionActive;
    console.log(dataPage);
    
    switch(currentPage) {
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
        console.log(token);
        const response = await updateEmail(token);
        if(response?.isSuccessRequest) next('/mon-compte');
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
          // page 404 ?
          next('/connexion');
          break;
      }
    }    
});




export default router;