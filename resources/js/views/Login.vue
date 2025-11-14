<template>
    <div class="min-h-screen bg-slate-950 text-slate-50">
        <div class="mx-auto flex min-h-screen max-w-3xl flex-col px-6 py-10">
            <Navbar />
            <main class="mx-auto mt-10 w-full max-w-md rounded-2xl bg-slate-900/60 p-8 shadow-2xl">
                <h1 class="text-2xl font-semibold">Iniciar Sesión</h1>
                <p class="mt-2 text-sm text-slate-400">
                    Accede a tu cuenta para administrar tus gimnasios y tenants.
                </p>

                <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                    <div>
                        <label class="text-sm font-medium text-slate-300" for="email">Correo</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-2 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                            required
                            autocomplete="email"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-300" for="password">Contraseña</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-2 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                            required
                            autocomplete="current-password"
                        />
                    </div>
                    <p v-if="errorMessage" class="text-sm text-rose-400">{{ errorMessage }}</p>
                    <button
                        class="w-full rounded-lg bg-emerald-500 px-4 py-2 font-semibold text-white transition hover:bg-emerald-400 disabled:opacity-50"
                        :disabled="loading"
                        type="submit"
                    >
                        {{ loading ? 'Accediendo...' : 'Entrar' }}
                    </button>
                </form>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

import Navbar from '@/components/Navbar.vue';
import { useAuthStore } from '@/stores/authStore';

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();

const form = reactive({
    email: '',
    password: '',
});

const loading = ref(false);
const errorMessage = ref('');

const handleSubmit = async (): Promise<void> => {
    loading.value = true;
    errorMessage.value = '';

    try {
        await auth.login(form);
        const redirect = (route.query.redirect as string) ?? '/dashboard';
        router.push(redirect);
    } catch (error) {
        console.error(error);
        errorMessage.value = 'Credenciales inválidas, intenta nuevamente.';
    } finally {
        loading.value = false;
    }
};
</script>
