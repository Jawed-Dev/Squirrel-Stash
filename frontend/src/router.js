// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import PageLogin from '@/pages/PageLogin.vue'; 
import PageCreateAcc from '@/pages/PageCreateAcc.vue'; 
import PageDashboard from '@/pages/PageDashboard.vue'; 
import PagePurchases from "@/pages/PagePurchases.vue";
import PageTemporary from '@/pages/PageTemporary.vue';
import useConfigFetchGetPage from "@/composable/useConfigFetchGetPage";
import { getLStorageAuthToken } from "@/composable/useLocalStorage";

const routes = [
  { path: '/', component: PageTemporary, meta: { page: 'index'}},
  { path: '/connexion', component: PageLogin, meta: { page: 'pageLogin'}},
  { path: '/inscription', component: PageCreateAcc, meta: { page: 'createAcc' }},
  { path: '/tableau-de-bord', component: PageDashboard, meta: { page: 'pageDashboard' }},
  { path: '/list-achats', component: PagePurchases, meta: { page: 'purchases' }}
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record)) {  
  
    const page = to.meta.page;  
    const localToken = getLStorageAuthToken();

    const dataPage = await useConfigFetchGetPage(`pageIndex`, localToken);
    console.log(dataPage);
    const isSessionActive = dataPage?.isSessionActive;
    
    switch(page) {
      case 'index' : {
        (isSessionActive) ? next('/tableau-de-bord') : next('/connexion');
        break;
      }
      case 'pageLogin' : {
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