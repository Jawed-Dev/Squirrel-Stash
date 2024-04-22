// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from './pages/Home.vue'; // Assurez-vous que le chemin est correct
import createAcc from './pages/CreateAcc.vue'; // Assurez-vous que le chemin est correct

const routes = [
  { path: '/', component: Home },
  { path: '/create-acc', component: createAcc }
];

const router = createRouter({
  history: createWebHistory(), // Assurez-vous que la base URL est correcte
  routes
});

export default router;