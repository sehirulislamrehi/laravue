
//import pluggins
import vuetify from './vuetify';
import router from './router';

window.Vue = require('vue');

//import components
import Home from './components/HomeComponent.vue';


const app = new Vue({
    el: '#app',
    vuetify,
    router,
    render: h => h(Home),
    mode : 'history'
});