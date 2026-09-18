import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Exam from '../views/Exam.vue'
import Result from '../views/Result.vue'

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/login' },
    { path: '/login', name: 'login', component: Login },
    { path: '/exam', name: 'exam', component: Exam },
    { path: '/result/:id', name: 'result', component: Result },
  ],
})
