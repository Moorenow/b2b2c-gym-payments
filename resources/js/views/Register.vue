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
                        <div class="relative mt-2">
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 pr-12 focus:border-emerald-400 focus:outline-none"
                                required
                            />
                            <button
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-slate-200"
                                type="button"
                                @click="togglePasswordVisibility"
                            >
                                <span class="sr-only">Mostrar/Ocultar contraseña</span>
                                <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18m-1.5-3.75C17.5 20 15 21 12 21c-6 0-10-6-10-9 0-1.17.35-2.28.97-3.25M9.88 9.88A3 3 0 0114.12 14.12M9 5.1C9.94 5.04 10.95 5 12 5c6 0 10 6 10 9 0 .7-.1 1.38-.3 2.03" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7S2 12 2 12zm10 3a3 3 0 100-6 3 3 0 000 6z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-300" for="password_confirmation">Confirmar Contraseña</label>
                        <div class="relative mt-2">
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showPasswordConfirmation ? 'text' : 'password'"
                                class="w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 pr-12 focus:border-emerald-400 focus:outline-none"
                                required
                            />
                            <button
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-slate-200"
                                type="button"
                                @click="togglePasswordConfirmationVisibility"
                            >
                                <span class="sr-only">Mostrar/Ocultar confirmación</span>
                                <svg v-if="showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18m-1.5-3.75C17.5 20 15 21 12 21c-6 0-10-6-10-9 0-1.17.35-2.28.97-3.25M9.88 9.88A3 3 0 0114.12 14.12M9 5.1C9.94 5.04 10.95 5 12 5c6 0 10 6 10 9 0 .7-.1 1.38-.3 2.03" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7S2 12 2 12zm10 3a3 3 0 100-6 3 3 0 000 6z" />
                                </svg>
                            </button>
                        </div>
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
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const togglePasswordVisibility = (): void => {
    showPassword.value = !showPassword.value;
};

const togglePasswordConfirmationVisibility = (): void => {
    showPasswordConfirmation.value = !showPasswordConfirmation.value;
};

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
