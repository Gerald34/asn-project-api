import VueRouter from 'vue-router'

import Signin from '../components/signin/signin.component';
import AdminDashboard from '../components/dashboard/dashboard.component';

const routes = [
    {
        path: '/',
        name: 'signin',
        component: Signin,
        meta: {
            auth: false
        }
    },
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: {
            auth: {roles: 1, redirect: {name: 'signin'}, forbiddenRedirect: '/403'}
        }
    },
];

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});

export default router;
