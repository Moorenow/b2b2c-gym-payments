<template>
    <div class="min-h-screen bg-slate-950 text-slate-50">
        <div class="mx-auto flex min-h-screen max-w-3xl flex-col px-6 py-10">
            <Navbar />
            <main class="mx-auto mt-10 w-full max-w-md rounded-2xl bg-slate-900/60 p-8 shadow-2xl">
                <h1 class="text-2xl font-semibold">Crear una cuenta</h1>
                <p class="mt-2 text-sm text-slate-400">Registra a tu gimnasio para comenzar a cobrar en minutos.</p>

                <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                    <div>
                        <label class="text-sm font-medium text-slate-300" for="name">Nombre</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-2 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                            required
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-300" for="email">Correo</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-2 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                            required
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
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-300" for="password_confirmation">Confirmar Contraseña</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-2 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                            required
                        />
                    </div>
                    <p v-if="errorMessage" class="text-sm text-rose-400">{{ errorMessage }}</p>
                    <button
                        class="w-full rounded-lg bg-emerald-500 px-4 py-2 font-semibold text-white transition hover:bg-emerald-400 disabled:opacity-50"
                        :disabled="loading"
                        type="submit"
                    >
                        {{ loading ? 'Creando cuenta...' : 'Registrarme' }}
                    </button>
                </form>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

import Navbar from '@/components/Navbar.vue';
import { useAuthStore } from '@/stores/authStore';

const auth = useAuthStore();
const router = useRouter();

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const loading = ref(false);
const errorMessage = ref('');

const handleSubmit = async (): Promise<void> => {
    loading.value = true;
    errorMessage.value = '';

    try {
        await auth.register(form);
        router.push({ name: 'dashboard' });
    } catch (error) {
        console.error(error);
        errorMessage.value = 'No pudimos crear tu cuenta, verifica tus datos.';
    } finally {
        loading.value = false;
    }
};
</script>
