import Dashboard from '@js/views/layouts/Dashboard';

import EnquiryIndex from '@js/views/enquiries/Index';
import EnquiryShow from '@js/views/enquiries/Show';

let routes = [
    {
        path: '/enquiries',
        component: Dashboard,
        meta: { section: 'enquiries' },

        children: [
            {
                path: '',
                name: 'enquiries.index',
                component: EnquiryIndex,
                meta: {
                    title: 'Your enquiries'
                }
            },
            {
                path: ':id',
                name: 'enquiries.show',
                component: EnquiryShow,
                meta: {
                    title: 'View enquiry'
                }
            }
        ]
    }
];

export default routes;