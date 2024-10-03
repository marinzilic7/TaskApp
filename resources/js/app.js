import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap-icons/font/bootstrap-icons.css';
import router from './router';


import App from './App.vue';

const app = createApp(App);

app.use(router);


app.mount('#app');
