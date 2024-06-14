/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import VueGoogleMaps from '@fawmi/vue-google-maps';
import ExampleComponent from './components/ExampleComponent.vue';
import OptimalPathComponent from './components/OptimalPathComponent.vue';

/**
 * Wait for the DOM to be fully loaded before accessing meta tag.
 */
document.addEventListener('DOMContentLoaded', () => {
    const googleMapsApiKey = document.querySelector('meta[name="google-maps-api-key"]').getAttribute('content');
    
    const app = createApp({});

    app.use(VueGoogleMaps, {
        load: {
            key: googleMapsApiKey,
            libraries: 'places',
        },
    });

    app.component('example-component', ExampleComponent);
    app.component('optimal-path-component', OptimalPathComponent);

    /**
     * Finally, we will attach the application instance to a HTML element with
     * an "id" attribute of "app". This element is included with the "auth"
     * scaffolding. Otherwise, you will need to add an element yourself.
     */
    app.mount('#app');
});
