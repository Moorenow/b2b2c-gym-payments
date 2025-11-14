import { createRouter, createWebHistory } from 'vue-router';

import AdminRoles from '@/views/AdminRoles.vue';
import Dashboard from '@/views/Dashboard.vue';
import Home from '@/views/Home.vue';
import Login from '@/views/Login.vue';
import PlansAndMembers from '@/views/PlansAndMembers.vue';
import Register from '@/views/Register.vue';
import { useAuthStore } from '@/stores/authStore';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: Home },
        { path: '/login', name: 'login', component: Login, meta: { guestOnly: true } },
        { path: '/register', name: 'register', component: Register, meta: { guestOnly: true } },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
            meta: { requiresAuth: true, requiresRole: ['super_admin', 'gym_admin'] },
        },
        {
            path: '/admin/roles',
            name: 'admin.roles',
            component: AdminRoles,
            meta: { requiresAuth: true, requiresRole: ['super_admin', 'gym_admin'] },
        },
        {
            path: '/admin/plans',
            name: 'admin.plans',
            component: PlansAndMembers,
            meta: { requiresAuth: true, requiresRole: ['super_admin', 'gym_admin'] },
        },
    ],
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();

    if (!auth.initialized) {
        await auth.bootstrap();
    }

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return { name: 'login', query: { redirect: to.fullPath } };
    }

    if (to.meta.guestOnly && auth.isAuthenticated) {
        return { name: 'dashboard' };
    }

    if (to.meta.requiresRole && Array.isArray(to.meta.requiresRole)) {
        const hasRequiredRole = to.meta.requiresRole.some((role) => auth.hasRole(role as string));

        if (!hasRequiredRole) {
            return { name: 'home' };
        }
    }

    return true;
});

export default router;
