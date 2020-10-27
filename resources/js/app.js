
//import pluggins
import vuetify from './vuetify';
import router from './router';
import bootstrap from './bootstrap';

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
