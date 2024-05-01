// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue'; 
import createAcc from './pages/CreateAcc.vue'; 
import Dashboard from './pages/Dashboard.vue'; 

const routes = [
  { path: '/', component: Login },
  { path: '/connexion', component: Login },
  { path: '/inscription', component: createAcc },
  { path: '/home', component: Dashboard }
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});

export default router;