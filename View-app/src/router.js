// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from './pages/Home.vue'; 
import createAcc from './pages/CreateAcc.vue'; 
import ResumePage from './pages/Resume.vue'; 

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Home },
  { path: '/create-acc', component: createAcc },
  { path: '/resume', component: ResumePage }
];

const router = createRouter({
  history: createWebHistory(), 
  routes
});

export default router;