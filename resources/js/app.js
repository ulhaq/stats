import App from './App.vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import moment from 'moment';

require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

Vue.mixin({
    data: function () {
        return {
            get moment() {
                return moment;
            },
            BaseUrl: "http://localhost:8000/api",
        }
    }
})

Vue.component('loading', require('./components/loading.vue').default);

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const routes = [
    { path: '/', redirect: '/sessions' },

    { name: 'sessions', path: '/sessions', component: require('./components/sessions/index').default, },
    { name: 'session-preview', path: '/sessions/:id', component: require('./components/sessions/preview').default, },
    
    { name: 'counts', path: '/counts', component: require('./components/counts/index').default, },
    
    { name: 'users', path: '/users', component: require('./components/users/index').default, },
    
    { name: '404', path: '*', component: require('./components/404').default, },
]

var router = new VueRouter({
    routes: routes,
    mode: 'history'
})

const app = new Vue({
    el: '#app',
    router,
    render: h => h(App),
});
