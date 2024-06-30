// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import PageLogin from '@/pages/PageLogin.vue'; 
import PageCreateAcc from '@/pages/PageCreateAcc.vue'; 
import PageDashboard from '@/pages/PageDashboard.vue'; 
import PagePurchases from "@/pages/PagePurchases.vue";
import PageTemporary from '@/pages/PageTemporary.vue';
import PageForgotPass from '@/pages/PageForgotPass.vue';
import PageResetPass from '@/pages/PageResetPass.vue';

import useConfigFetchGetPage from "@/composable/useConfigFetchGetPage";
import { getLStorageAuthToken } from "@/composable/useLocalStorage";
import { isValidResetPassToken } from "@/composable/useBackendGetData";

const routes = [
  { path: '/', component: PageTemporary, meta: { page: 'pageIndex'}},
  { path: '/connexion', component: PageLogin, meta: { page: 'pageLogin'}},
  { path: '/inscription', component: PageCreateAcc, meta: { page: 'pageRegister' }},
  { path: '/tableau-de-bord', component: PageDashboard, meta: { page: 'pageDashboard' }},
  { path: '/mot-de-passe-oublie', component: PageForgotPass, meta: { page: 'pageForgotPass' }},
  { path: '/reinitialiser-mot-de-passe', component: PageResetPass, meta: { page: 'pageResetPass' }},
  { path: '/list-achats', component: PagePurchases, meta: { page: 'purchases' }},
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record)) {  
    const currentPage = to.meta.page;  
    const localToken = getLStorageAuthToken();

    const dataPage = await useConfigFetchGetPage(currentPage, localToken);
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
      case 'pageResetPass' : {
        const isParamUrlValid = to.query.token;
        const isValidToken = await isValidResetPassToken(to.query.token);
        if(!isParamUrlValid || !isValidToken) next('/connexion'); 
        (isSessionActive) ? next('/tableau-de-bord') : next();
        break;
      }
      case 'pageDashboard' : {
        (isSessionActive) ? next() : next('/connexion');
        break;
      }
      default : {
          // page 404 ?
          next();
          break;
      }
    }    
  } 
});




export default router;