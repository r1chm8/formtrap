import './bootstrap';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import AuthContainer from './components/Container';
import Password from '@js/components/form/Password';
// Register components
Vue.component('icon', FontAwesomeIcon);
Vue.component('auth-container', AuthContainer);
Vue.component('password', Password);

// lLbraries
import icons from './lib/icons';
icons.register();

new Vue({
    el: '#app'
});
