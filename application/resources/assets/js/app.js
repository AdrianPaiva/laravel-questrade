
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import ElementUI from 'element-ui';
import './../../../node_modules/element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI);
// Vue.use(require('vue-moment'));

// Laravel Passport components
Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

// Auth components
Vue.component('register-component', require('./components/auth/RegisterComponent.vue').default);
Vue.component('login-component', require('./components/auth/LoginComponent.vue').default);

// layout components
Vue.component('side-nav-component', require('./components/layouts/SidenavComponent.vue').default);
Vue.component('form-error', require('./components/forms/FormError.vue').default);

// Questrade
Vue.component('questrade-authorize', require('./components/questrade/Authorize.vue').default);

const app = new Vue({
    el: '#app',
    data: {
        toggleMenu: false,
    }
});
