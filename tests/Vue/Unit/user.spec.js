import Vuex from 'vuex'; 
import expect from 'expect';
import moxios from 'moxios';
import { createLocalVue } from '@vue/test-utils';

import userModule from '../../../resources/js/store/modules/user';
import loaderModule from '../../../resources/js/store/modules/loader';

describe('User', () => {
    let Vue;
    let store;

    beforeEach(() => {
        Vue = createLocalVue();
        Vue.use(Vuex);

        moxios.install();

        store = new Vuex.Store({
            modules: {
                loader: loaderModule,
                user: {
                    ...userModule,
                    state: {
                        data: {}
                    }
                }
            }
        });
    });

    afterEach(() => {
        moxios.uninstall();
    });

    it('fetches the authenticated users data from the api', done => {
        let user = mockUser();

        expect(store.getters['user/data']).toEqual({});

        store.dispatch('user/fetch');

        moxios.wait(() => {
            let request = moxios.requests.mostRecent();
            request.respondWith({
                status: 200,
                response: { data: user }
            }).then(response => {
                expect(response.config.url).toBe('/api/user');
                expect(store.getters['user/data']).toEqual(user);
                done();
            });
        });
    });

    it('can logout and clear the authenticated user data', done => {
        let user = mockUser();

        store.commit('user/set', user);
        expect(store.getters['user/data']).toEqual(user);

        store.dispatch('user/logout');

        moxios.wait(() => {
            let request = moxios.requests.mostRecent();
            request.respondWith({
                status: 200
            }).then(response => {
                expect(response.config.url).toBe('/logout');
                expect(store.getters['user/data']).toEqual({});
                done();
            });
        });
    });

    const mockUser = () => {
        return {
            id: 1,
            name: 'Jack Robertson',
            email: 'jack@robertson.com'
        };
    }
});
