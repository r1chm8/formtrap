import './bootstrap';

import router from './router';
import store from './store';
import App from './views/App';

import { mapActions } from 'vuex';  

import Toasted from 'vue-toasted';
Vue.use(Toasted, {
    theme: 'default',
    position: 'bottom-right',
    action: {
        text: 'Close',
        onClick(e, toastObject) {
            toastObject.goAway(0);
        }
    }
});

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Loader from './components/ui/loader';
import SubHeader from './views/partials/SubHeader';

// Register components
Vue.component('icon', FontAwesomeIcon);
Vue.component('loader', Loader);
Vue.component('sub-header', SubHeader);

// lLbraries
import icons from './lib/icons';
icons.register();

Vue.mixin({
    methods: {
        ...mapActions({
            startLoading: 'loader/startLoading',
            stopLoading: 'loader/stopLoading'
        })
    }
});

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});
