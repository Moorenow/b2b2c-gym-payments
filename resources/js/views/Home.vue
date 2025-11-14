<template>
    <div class="min-h-screen bg-slate-950 text-slate-50">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6">
            <Navbar />
            <main class="flex flex-1 flex-col items-center justify-center gap-6 text-center">
                <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">Plataforma para gimnasios</p>
                <h1 class="text-4xl font-semibold md:text-5xl">
                    Centraliza pagos, membresías y reportes
                </h1>
                <p class="max-w-2xl text-slate-400">
                    Gestiona franquicias, administra roles y controla los flujos desde una sola
                    plataforma.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <RouterLink
                        v-if="!auth.isAuthenticated"
                        to="/register"
                        class="rounded-lg bg-emerald-500 px-6 py-3 font-semibold text-white shadow transition hover:bg-emerald-400"
                    >
                        Comenzar Gratis
                    </RouterLink>
                    <RouterLink
                        v-if="!auth.isAuthenticated"
                        to="/login"
                        class="rounded-lg border border-slate-700 px-6 py-3 font-semibold text-white transition hover:border-slate-500"
                    >
                        Iniciar Sesión
                    </RouterLink>
                    <RouterLink
                        v-else
                        to="/dashboard"
                        class="rounded-lg bg-emerald-500 px-6 py-3 font-semibold text-white shadow transition hover:bg-emerald-400"
                    >
                        Ir al Dashboard
                    </RouterLink>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router';

import Navbar from '@/components/Navbar.vue';
import { useAuthStore } from '@/stores/authStore';

const auth = useAuthStore();
</script>
