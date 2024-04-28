// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue'; 
import createAcc from './pages/CreateAcc.vue'; 
import Home from './pages/Home.vue'; 

const routes = [
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/create-acc', component: createAcc },
  { path: '/home', component: Home }
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});

export default router;