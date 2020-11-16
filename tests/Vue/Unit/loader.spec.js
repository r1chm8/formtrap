import Vuex from 'vuex'; 
import expect from 'expect';
import { createLocalVue } from '@vue/test-utils';

import loaderModule from '../../../resources/js/store/modules/loader';

describe('Loader', () => {
    let Vue;
    let store;

    beforeEach(() => {
        Vue = createLocalVue();
        Vue.use(Vuex);

        store = new Vuex.Store({
            ...loaderModule,
            state: {
                loading: []
            }
        });
    });

    it('can start loading an item', () => {
        expect(store.getters.isLoading()).toBe(false);

        store.dispatch('startLoading', 'foo');
        expect(store.getters.isLoading()).toBe(true);
    });

    it('can load multiple items at once', () => {
        expect(store.getters.isLoading()).toBe(false);

        store.dispatch('startLoading', 'foo');
        store.dispatch('startLoading', 'bar');
        expect(store.getters.isLoading()).toBe(true);
    });

    it('can find an item being loaded', () => {
        expect(store.getters.isLoading()).toBe(false);

        store.dispatch('startLoading', 'foo');
        expect(store.getters.isLoading('foo')).toBe(true);
    });

    it('can not find an item load being loaded', () => {
        expect(store.getters.isLoading()).toBe(false);

        store.dispatch('startLoading', 'bar');
        expect(store.getters.isLoading('foo')).toBe(false);
    });

    it('can match an item being loaded', () => {
        expect(store.getters.isLoading()).toBe(false);

        store.dispatch('startLoading', 'foo*bar');
        expect(store.getters.isLoading('foo*')).toBe(true);
    });

    it('can stop loading an item', () => {
        expect(store.getters.isLoading()).toBe(false);

        store.dispatch('startLoading', 'foo');
        expect(store.getters.isLoading()).toBe(true);

        store.dispatch('stopLoading', 'foo');
        expect(store.getters.isLoading()).toBe(false);
    });

    it('can stop loading a matched items', () => {
        expect(store.getters.isLoading()).toBe(false);

        store.dispatch('startLoading', 'foo*bar');
        store.dispatch('startLoading', 'foo*baz');
        expect(store.getters.isLoading()).toBe(true);

        store.dispatch('stopLoading', 'foo*');
        expect(store.getters.isLoading()).toBe(false);
    });

    it('can clear all items being loaded', () => {
        store.dispatch('startLoading', 'foo');
        store.dispatch('startLoading', 'bar');
        expect(store.getters.isLoading()).toBe(true);

        store.dispatch('stopLoading', );
        expect(store.getters.isLoading()).toBe(false);
    });
});
