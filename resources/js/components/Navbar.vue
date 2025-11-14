<template>
    <header class="flex flex-wrap items-center justify-between gap-4 py-6">
        <RouterLink to="/" class="flex items-center gap-3 font-semibold text-lg">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-white">GYM</span>
            <span>Gym Payments</span>
        </RouterLink>
        <nav>
                <div v-if="!auth.isAuthenticated" class="flex gap-3">
                    <RouterLink
                        to="/login"
                        class="rounded-lg border border-slate-700 px-4 py-2 text-sm font-medium transition hover:border-slate-500 hover:text-white"
                    >
                    Iniciar Sesión
                </RouterLink>
                <RouterLink
                    to="/register"
                    class="rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-400"
                >
                    Registrarse
                </RouterLink>
            </div>
            <div v-else class="relative">
                <button
                    class="flex items-center gap-3 rounded-lg border border-slate-700 px-4 py-2 text-sm font-medium transition hover:border-slate-500"
                    type="button"
                    @click="toggleMenu"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-800 text-xs font-bold uppercase">
                        {{ initials }}
                    </span>
                    {{ auth.displayName }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div
                    v-if="menuOpen"
                    class="absolute right-0 mt-2 w-48 divide-y divide-slate-200 rounded-lg border border-slate-200 bg-white py-1 text-sm text-slate-700 shadow-lg"
                >
                    <RouterLink to="/dashboard" class="block px-4 py-2 hover:bg-slate-100" @click="closeMenu">
                        Dashboard
                    </RouterLink>
                    <RouterLink
                        v-if="canManageRoles"
                        to="/admin/roles"
                        class="block px-4 py-2 hover:bg-slate-100"
                        @click="closeMenu"
                    >
                        Roles y Permisos
                    </RouterLink>
                    <button class="block w-full px-4 py-2 text-left hover:bg-slate-100" type="button" @click="handleLogout">
                        Cerrar Sesión
                    </button>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';

import { useAuthStore } from '@/stores/authStore';

const auth = useAuthStore();
const router = useRouter();
const menuOpen = ref(false);

const canManageRoles = computed(() => auth.hasRole('super_admin') || auth.hasRole('gym_admin'));

const initials = computed(() => {
    if (!auth.displayName) {
        return 'GY';
    }

    return auth.displayName
        .split(' ')
        .map((word) => word[0]?.toUpperCase())
        .join('')
        .slice(0, 2);
});

const toggleMenu = (): void => {
    menuOpen.value = !menuOpen.value;
};

const closeMenu = (): void => {
    menuOpen.value = false;
};

const afterEach = router.afterEach(() => {
    closeMenu();
});

onBeforeUnmount(() => {
    afterEach();
});

const handleLogout = async (): Promise<void> => {
    await auth.logout();
    closeMenu();
    router.push({ name: 'home' });
};
</script>
