import './bootstrap';

import { createApp } from 'vue';

const app = createApp({});

import AppComponent from './components/AppComponent.vue';


app.component('app-component', AppComponent);


app.mount('#app');