<template>
    <div class="min-h-screen bg-slate-950 text-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-10">
            <Navbar />
            <section class="mt-8 rounded-3xl bg-slate-900/60 p-8 shadow-2xl">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">Panel</p>
                        <h1 class="text-3xl font-semibold">Dashboard</h1>
                    </div>
                    <p class="text-sm text-slate-400">{{ userRoleCopy }}</p>
                </div>
                <div v-if="loading" class="mt-10 text-slate-400">Cargando métricas...</div>
                <div v-else class="mt-10 grid gap-6 md:grid-cols-3">
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
                        <p class="text-sm text-slate-400">Gimnasios Activos</p>
                        <p class="mt-2 text-3xl font-semibold">{{ dashboardData?.metrics.gyms ?? '--' }}</p>
                    </article>
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
                        <p class="text-sm text-slate-400">Miembros activos</p>
                        <p class="mt-2 text-3xl font-semibold">{{ dashboardData?.metrics.active_members ?? '--' }}</p>
                    </article>
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
                        <p class="text-sm text-slate-400">MRR estimado</p>
                        <p class="mt-2 text-3xl font-semibold">${{ dashboardData?.metrics.monthly_revenue ?? '--' }}</p>
                    </article>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

import Navbar from '@/components/Navbar.vue';
import { useAuthStore } from '@/stores/authStore';

const auth = useAuthStore();
const loading = ref(true);
const dashboardData = ref<{ message: string; can_manage_tenants: boolean; metrics: Record<string, number> } | null>(null);

const userRoleCopy = computed(() => {
    if (auth.hasRole('super_admin')) {
        return 'Rol: Super Admin (control de la plataforma)';
    }

    if (auth.hasRole('gym_admin')) {
        return 'Rol: Gym Admin (administrador del gimnasio)';
    }

    return 'Rol sin privilegios de dashboard';
});

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/dashboard');
        dashboardData.value = data;
    } finally {
        loading.value = false;
    }
});
</script>
