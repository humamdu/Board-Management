import { createApp } from 'vue';import { createPinia } from 'pinia';import router from './router';import App from './components/App.vue';import './bootstrap';
createApp(App).use(createPinia()).use(router).mount('#app');
