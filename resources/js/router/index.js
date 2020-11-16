import VueRouter from 'vue-router';
Vue.use(VueRouter);

import store from '@js/store';

import enquiryRoutes from './groups/enquiry';
import formRoutes from './groups/form';
import settingsRoutes from './groups/settings';

const router = new VueRouter({
    mode: 'history',
    
    routes: [
        {
            path: '/',
            name: 'home',
            redirect: { name: 'forms.index' }
        },
        
        ...enquiryRoutes,
        ...formRoutes,
        ...settingsRoutes
    ],

    scrollBehavior() {
        return { x: 0, y: 0 };
    }
});

router.beforeEach((to, from, next) => {
    let meta = to.matched[to.matched.length - 1].meta;
    
    Vue.toasted.clear();
    store.dispatch('loader/stopLoading');
    store.dispatch('panel/closeRight');

    if (meta.hasOwnProperty('title')) {
        document.title = meta.title + ' | Formtrap.io';
    }
    
    next();
});

router.afterEach((to, from) => {
    // google analytics tracking
    ga('set', 'page', to.path);
    ga('send', 'pageview');
});

axios.interceptors.response.use(response => response, error => {
    if (error.response.status === 401) {
        return window.location.href = '/login';
    }
    
    return Promise.reject(error);
});

export default router;
