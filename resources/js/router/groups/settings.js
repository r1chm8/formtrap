import SettingsLayout from '@js/views/settings/Layout';

import ChangeEmail from '@js/views/settings/ChangeEmail';
import ChangePassword from '@js/views/settings/ChangePassword';
import Subscription from '@js/views/settings/Subscription';
// import CloseAccount from '@js/views/settings/CloseAccount';
import General from '@js/views/settings/General';

let routes = [
    {
        path: '/settings',
        component: SettingsLayout,
        meta: { section: 'settings' },

        children: [
            {
                path: '',
                name: 'settings.general',
                component: General,
                meta: {
                    title: 'Manage account'
                }
            },
            {
                path: 'change-email',
                name: 'settings.change-email',
                component: ChangeEmail,
                meta: {
                    title: 'Change email'
                }
            },
            {
                path: 'change-password',
                name: 'settings.change-password',
                component: ChangePassword,
                meta: {
                    title: 'Change password'
                }
            },
            {
                path: 'subscription',
                name: 'settings.subscription',
                component: Subscription,
                meta: {
                    title: 'Subscription'
                }
            },
            // {
            //     path: 'close-account',
            //     name: 'settings.close-account',
            //     component: CloseAccount
            // }
        ]
    }
];

export default routes;
