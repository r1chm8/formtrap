import Vuex from 'vuex'; 
import expect from 'expect';
import { createLocalVue } from '@vue/test-utils';

import panelModule from '../../../resources/js/store/modules/panel';

describe('Panels', () => {
    let Vue;
    let store;

    beforeEach(() => {
        Vue = createLocalVue();
        Vue.use(Vuex);

        store = new Vuex.Store({
            ...panelModule,
            state: {
                rightIsOpen: false
            }
        });
    });

    it('can set right panel as opened', () => {
        expect(store.getters.rightIsOpen).toBe(false);

        store.dispatch('openRight');
        expect(store.getters.rightIsOpen).toBe(true);
    });

    it('can set right panel as closed', () => {
        store.dispatch('openRight');
        expect(store.getters.rightIsOpen).toBe(true);

        store.dispatch('closeRight');
        expect(store.getters.rightIsOpen).toBe(false);
    });
});
