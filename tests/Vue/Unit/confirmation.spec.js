import Vuex from 'vuex'; 
import expect from 'expect';
import { createLocalVue } from '@vue/test-utils';

import confirmationModule from '../../../resources/js/store/modules/confirmation';

describe('Confirmation', () => {
    let Vue;
    let store;

    beforeEach(() => {
        Vue = createLocalVue();
        Vue.use(Vuex);

        store = new Vuex.Store({
            ...confirmationModule,
            state: {
                isOpen: false,
                item: {}
            }
        });
    });

    it('can open', () => {
        expect(store.getters.isOpen).toBe(false);

        store.dispatch('open');
        expect(store.getters.isOpen).toBe(true);
    });

    it('can close', () => {
        store.dispatch('open');
        expect(store.getters.isOpen).toBe(true);

        store.dispatch('close');
        expect(store.getters.isOpen).toBe(false);
    });

    it('can open and set an item', () => {
        let item = mockItem();

        expect(store.getters.isOpen).toBe(false);
        expect(store.getters.item).toEqual({});

        store.dispatch('open', item);

        expect(store.getters.isOpen).toBe(true);
        expect(store.getters.item).toBe(item);
    });

    it('can close and clear the item', () => {
        let item = mockItem();

        store.dispatch('open', item);

        expect(store.getters.isOpen).toBe(true);
        expect(store.getters.item).toBe(item);

        store.dispatch('close');

        expect(store.getters.isOpen).toBe(false);
        expect(store.getters.item).toEqual({});
    });

    const mockItem = () => {
        return {
            foo: 'bar'
        };
    }
});
