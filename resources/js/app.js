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
            URL: "http://localhost:8000/api/",
        }
    }
})

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const routes = [
    { name: 'sessions', path: '/', component: require('./components/sessions/index').default, },
    { name: 'session-preview', path: '/sessions/:id', component: require('./components/sessions/preview').default, },
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
