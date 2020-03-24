import App from './App.vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import moment from 'moment';
import Chartkick from 'vue-chartkick'
import Chart from 'chart.js'

require('./bootstrap');

window.Vue = require('vue');

axios.defaults.withCredentials = true;

Vue.use(Chartkick.use(Chart))

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

function getCookie(a) {
    var b = document.cookie.match('(^|[^;]+)\\s*' + a + '\\s*=\\s*([^;]+)');
    return b ? b.pop() : null;
}

function loggedIn() {
    return getCookie('active') !== null;
}

Vue.mixin({
    data: function () {
        return {
            get moment() {
                return moment;
            },
            get loggedIn() {
                return loggedIn;
            },
            BaseUrl: "/api",
        }
    },
    methods: {
        utcToLocal: function (time) {
            return this.moment.utc(time).local();
        },
        deleteEntry: function (entry, id) {
            this.axios.delete(`${this.BaseUrl}/${entry}/${id}`).then(response => this.loadData()).catch(error => this.$router.push({name: entry}));
        }
    }
})

Vue.component('loading', require('./components/loading.vue').default);

Vue.component('charts', require('./components/graphs/charts.vue').default);

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const routes = [
    { path: '/', redirect: '/sessions' },

    { name: 'login', path: '/login', component: require('./components/auth/login').default, meta: { requiresVisitor: true }, },

    { name: 'users', path: '/users', component: require('./components/users/index').default, meta: { requiresAuth: true }, },
    { name: 'user-register', path: '/users/register', component: require('./components/users/register').default, meta: { requiresAuth: true }, },

    { name: 'sessions', path: '/sessions', component: require('./components/sessions/index').default, meta: { requiresAuth: true }, },
    { name: 'session-preview', path: '/sessions/:id', component: require('./components/sessions/preview').default, meta: { requiresAuth: true }, },
    
    { name: 'visitors', path: '/visitors', component: require('./components/visitors/index').default, meta: { requiresAuth: true }, },
    { name: 'visitor-preview', path: '/visitors/:visitor', component: require('./components/visitors/preview').default, meta: { requiresAuth: true }, },

    { name: 'counts', path: '/counts', component: require('./components/counts/index').default, meta: { requiresAuth: true }, },

    { name: 'graphs', path: '/graphs', component: require('./components/graphs/index').default, meta: { requiresAuth: true }, },    
    
    { name: '404', path: '*', component: require('./components/404').default, },
]

var router = new VueRouter({
    routes: routes,
    mode: 'history'
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!loggedIn()) {
            next({
                name: 'login',
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        if (loggedIn()) {
            next({
                name: 'sessions',
            })
        } else {
            next()
        }
    } else {
        next()
    }
});

const app = new Vue({
    el: '#app',
    router,
    render: h => h(App),
});
