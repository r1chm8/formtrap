import Vuex from 'vuex';
Vue.use(Vuex);

import confirmation from './modules/confirmation';
import errors from './modules/errors';
import form from './modules/form';
import loader from './modules/loader';
import panel from './modules/panel';
import user from './modules/user';

export default new Vuex.Store({
    modules: {
        confirmation,
        errors,
        form,
        loader,
        panel,
        user
    }
});
