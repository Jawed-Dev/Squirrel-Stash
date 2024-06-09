// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import PageLogin from '@/pages/PageLogin.vue'; 
import PageCreateAcc from '@/pages/PageCreateAcc.vue'; 
import PageDashboard from '@/pages/PageDashboard.vue'; 
import PagePurchases from "@/pages/PagePurchases.vue";
import PageTemporary from '@/pages/PageTemporary.vue';
import useFetch from "@/composables/useFetch";

const routes = [
  { path: '/', component: PageTemporary, meta: { page: 'pageIndex', apiPath: '/backend/index.php' }},
  { path: '/connexion', component: PageLogin, meta: { page: 'pageLogin', apiPath: '/backend/index.php' }},
  { path: '/inscription', component: PageCreateAcc, meta: { page: 'createAcc' }},
  { path: '/tableau-de-bord', component: PageDashboard, meta: { page: 'pageDashboard' }},
  { path: '/list-achats', component: PagePurchases, meta: { page: 'purchases' }}
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});



router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record)) {  // Assurez-vous d'accéder à la bonne propriété
  
    const page = to.meta.page;  

    switch(page) {
      case 'pageIndex' : {
        const dataPage = await useFetch(`pageIndex`);
        console.log(dataPage);
        (dataPage.isUserLog) ? next('/tableau-de-bord') : next('/connexion');
        break;
      }
      case 'pageLogin' : {
        const dataPage = await useFetch(`pageLogin`);
        (dataPage.isUserLog) ? next('/tableau-de-bord') : next();
        break;
      }
      case 'pageDashboard' : {
        const dataPage = await useFetch(`pageLogin`);
        (dataPage.isUserLog) ? next() : next('/connexion');
        break;
      }
    }

    
  } 
});




export default router;