import Dashboard from '@js/views/layouts/Dashboard';
import ManageLayout from '@js/views/forms/layouts/Manage';

import FormOverview from '@js/views/forms/Overview';
import FormIntegrations from '@js/views/forms/Integrations';
import FormVersions from '@js/views/forms/Versions';
import FormSettings from '@js/views/forms/Settings';

import FormIndex from '@js/views/forms/Index';
import FormFields from '@js/views/forms/Fields';

let routes = [
    {
        path: '/forms/:id/manage',
        component: ManageLayout,
        meta: { section: 'forms' },

        children: [
            {
                path: '',
                name: 'forms.manage',
                component: FormOverview,
                meta: {
                    title: 'Form overview'
                }
            },
            {
                path: 'integrations',
                name: 'forms.manage.integrations',
                component: FormIntegrations,
                meta: {
                    title: 'Manage integrations'
                }
            },
            {
                path: 'versions',
                name: 'forms.manage.versions',
                component: FormVersions,
                meta: {
                    title: 'Manage form versions'
                }
            },
            {
                path: 'settings',
                name: 'forms.manage.settings',
                component: FormSettings,
                meta: {
                    title: 'Form settings'
                }
            }
        ]
    },
    {
        path: '/forms',
        component: Dashboard,
        meta: { section: 'forms' },

        children: [
            {
                path: '',
                name: 'forms.index',
                component: FormIndex,
                meta: {
                    title: 'Your forms'
                }
            },
            {
                path: ':id/versions/:versionId/fields',
                name: 'forms.fields',
                component: FormFields,
                meta: {
                    title: 'Manage form fields'
                }
            }
        ]
    }
];

export default routes;