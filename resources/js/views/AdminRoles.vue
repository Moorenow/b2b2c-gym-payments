<template>
    <div class="min-h-screen bg-slate-950 text-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-10">
            <Navbar />

            <section class="mt-10 rounded-3xl bg-slate-900/60 p-8 shadow-2xl">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">Administración</p>
                        <h1 class="text-3xl font-semibold">Roles y Permisos</h1>
                    </div>
                    <span class="rounded-full border border-emerald-400 px-4 py-1 text-sm text-emerald-300">
                        Solo super admin y gym admin
                    </span>
                </div>

                <div v-if="loading" class="mt-10 text-slate-400">Cargando información...</div>

                <div v-else class="mt-10 space-y-10">
                    <div class="grid gap-4 md:grid-cols-2">
                        <article v-for="role in roles" :key="role.id" class="rounded-2xl border border-slate-800 p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-semibold capitalize">{{ role.name.replace('_', ' ') }}</h2>
                                <span class="rounded-full bg-slate-800 px-3 py-1 text-xs uppercase tracking-[0.2em]">
                                    {{ role.permissions.length }} permisos
                                </span>
                            </div>
                            <ul class="mt-4 space-y-2 text-sm text-slate-400">
                                <li v-for="permission in role.permissions" :key="permission.id" class="rounded bg-slate-900/70 px-3 py-2">
                                    {{ permission.name }}
                                </li>
                            </ul>
                        </article>
                    </div>

                    <div class="rounded-2xl border border-slate-800 p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h2 class="text-xl font-semibold">Usuarios con acceso</h2>
                            <p class="text-sm text-slate-400">
                                Cambia el rol asignado a cada usuario para controlar sus permisos.
                            </p>
                        </div>
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead>
                                    <tr class="text-slate-400">
                                        <th class="px-4 py-2 font-medium">Nombre</th>
                                        <th class="px-4 py-2 font-medium">Correo</th>
                                        <th class="px-4 py-2 font-medium">Rol</th>
                                        <th class="px-4 py-2 font-medium"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="user in users"
                                        :key="user.id"
                                        class="border-t border-slate-800 text-slate-200"
                                    >
                                        <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                                        <td class="px-4 py-3 text-slate-400">{{ user.email }}</td>
                                        <td class="px-4 py-3">
                                            <select
                                                v-model="user.selectedRole"
                                                class="w-48 rounded-lg border border-slate-700 bg-slate-900/60 px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none"
                                            >
                                                <option v-for="role in assignableRoles" :key="role" :value="role">
                                                    {{ formatRole(role) }}
                                                </option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button
                                                class="rounded-lg border border-emerald-400 px-4 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-400 hover:text-slate-900 disabled:opacity-50"
                                                :disabled="user.updating"
                                                @click="() => updateUserRole(user)"
                                            >
                                                {{ user.updating ? 'Guardando...' : 'Guardar' }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p v-if="errorMessage" class="mt-4 text-sm text-rose-400">{{ errorMessage }}</p>
                        <p v-if="successMessage" class="mt-4 text-sm text-emerald-400">{{ successMessage }}</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';

import Navbar from '@/components/Navbar.vue';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    permissions: Permission[];
}

interface UserRecord {
    id: number;
    name: string;
    email: string;
    roles: { name: string }[];
    selectedRole: string;
    updating?: boolean;
}

const loading = ref(true);
const roles = ref<Role[]>([]);
const users = ref<UserRecord[]>([]);
const errorMessage = ref('');
const successMessage = ref('');

const assignableRoles = ['super_admin', 'gym_admin'];

const formatRole = (role: string): string => role.replace('_', ' ').toUpperCase();

const fetchData = async (): Promise<void> => {
    loading.value = true;
    errorMessage.value = '';

    try {
        const { data } = await axios.get<{
            roles: Role[];
            users: { id: number; name: string; email: string; roles: { name: string }[] }[];
        }>('/api/admin/roles');

        roles.value = data.roles;
        users.value = data.users.map((user) => ({
            ...user,
            selectedRole: user.roles[0]?.name ?? 'gym_admin',
            updating: false,
        }));
    } catch (error) {
        console.error(error);
        errorMessage.value = 'No pudimos cargar los datos de roles. Intenta nuevamente.';
    } finally {
        loading.value = false;
    }
};

const updateUserRole = async (user: UserRecord): Promise<void> => {
    user.updating = true;
    errorMessage.value = '';
    successMessage.value = '';

    try {
        await axios.post(`/api/admin/users/${user.id}/role`, {
            role: user.selectedRole,
        });
        successMessage.value = `Rol actualizado para ${user.name}.`;
        await fetchData();
    } catch (error) {
        console.error(error);
        errorMessage.value = 'No pudimos actualizar el rol. Intenta nuevamente.';
    } finally {
        user.updating = false;
    }
};

onMounted(() => {
    fetchData();
});
</script>
