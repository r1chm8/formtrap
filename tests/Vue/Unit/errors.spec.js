import Vuex from 'vuex'; 
import expect from 'expect';
import { createLocalVue } from '@vue/test-utils';

import errorsModule from '../../../resources/js/store/modules/errors';

describe('Errors', () => {
    let Vue;
    let store;
    let error;
    let errors;

    beforeEach(() => {
        Vue = createLocalVue();
        Vue.use(Vuex);

        store = new Vuex.Store({
            ...errorsModule,
            state: {
                list: {}
            }
        });

        errors = { 
            foo: ['The foo field is required.'],
            bar: ['The bar field is required.']
        };

        error = {
            key: 'baz',
            value: 'The baz field is required.'
        };
    });

    it('can set errors', () => {
        expect(store.getters.list).toEqual({});

        store.dispatch('set', errors);
        expect(store.getters.list).toEqual(errors);
    });

    it('can set a single error', () => {
        store.dispatch('setSingle', error);
        expect(store.getters.list).toHaveProperty(error.key, [error.value]);
    });

    it('can append a single error', () => {
        store.dispatch('set', errors);
        expect(store.getters.list).toEqual(errors);

        store.dispatch('setSingle', error);
        expect(store.getters.list).toEqual({
            ...errors,
            [error.key]: [error.value]
        });
    });

    it('can clear a single error', () => {
        store.dispatch('set', errors);
        expect(store.getters.list).toEqual(errors);

        store.dispatch('clearSingle', 'bar');
        expect(store.getters.list).toHaveProperty('foo');
        expect(store.getters.list).not.toHaveProperty('bar');
    });

    it('can returns false if it has no errors', () => {
        expect(store.getters.any).toBe(false);
    });

    it('can returns true if it has errors', () => {
        store.dispatch('set', errors);
        expect(store.getters.any).toBe(true);
    });

    it('can check for a specific error', () => {
        expect(store.getters.contains('foo')).toBe(false);

        store.dispatch('set', errors);
        expect(store.getters.contains('foo')).toBe(true);
    });

    it('can return a specific error message', () => {
        store.dispatch('set', errors);
        expect(store.getters.getMessage('foo')).toBe(errors.foo[0]);
    });

    it('can clear all errors', () => {
        store.dispatch('set', errors);
        expect(store.getters.list).toEqual(errors);

        store.dispatch('clear');
        expect(store.getters.list).toEqual({});
    });
});
