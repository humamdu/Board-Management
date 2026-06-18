import {createRouter,createWebHistory} from 'vue-router';import Dashboard from '../views/Dashboard.vue';import Board from '../views/Board.vue';
export default createRouter({history:createWebHistory(),routes:[{path:'/',component:Dashboard},{path:'/boards/:id',component:Board,props:true}]});
