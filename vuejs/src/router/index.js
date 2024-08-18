import AppLayout from '@/layout/AppLayout.vue';
import { useAuthStore } from '@/stores';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: AppLayout,
            children: [
                {
                    path: '/',
                    name: 'dashboard',
                    component: () => import('@/views/Dashboard.vue')
                },
                {
                    path: '/quote',
                    name: 'quote',
                    component: () => import('@/views/Quote.vue')
                },
                {
                    path: '/history',
                    name: 'history',
                    component: () => import('@/views/History.vue')
                }
            ]
        },
        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        }
    ]
});

router.beforeEach(async (to) => {
    const publicPages = ['/auth/login'];
    const authRequired = !publicPages.includes(to.path);
    const auth = useAuthStore();

    if (authRequired && !auth.user) {
        auth.returnUrl = to.fullPath;
        return '/auth/login';
    }
});

export default router;
