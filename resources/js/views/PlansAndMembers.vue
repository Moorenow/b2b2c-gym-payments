<template>
    <div class="min-h-screen bg-slate-950 text-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-10">
            <Navbar />

            <nav class="mt-6">
                <ol class="flex items-center gap-6">
                    <li class="flex-1">
                        <button
                            type="button"
                            class="w-full text-left transition"
                            :class="isPlansStep ? '' : 'opacity-70 hover:opacity-100'"
                            :disabled="isPlansStep"
                            @click="goToPlans"
                        >
                            <div class="flex items-center text-sm font-semibold" :class="isPlansStep ? 'text-emerald-400' : 'text-slate-500'">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-full"
                                    :class="isPlansStep ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-400'"
                                >
                                    1
                                </span>
                                <span class="ml-3 uppercase tracking-[0.3em]">Planes</span>
                            </div>
                            <div class="mt-2 h-1 rounded-full" :class="isPlansStep ? 'bg-emerald-500' : 'bg-slate-800'"></div>
                        </button>
                    </li>
                    <li class="flex-1">
                        <button
                            type="button"
                            class="w-full text-left transition"
                            :class="!isPlansStep && hasPlans ? '' : 'opacity-70 hover:opacity-100'"
                            :disabled="!hasPlans"
                            @click="goToMembers"
                        >
                            <div class="flex items-center text-sm font-semibold" :class="!isPlansStep && hasPlans ? 'text-emerald-400' : 'text-slate-500'">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-full"
                                    :class="!isPlansStep && hasPlans ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-400'"
                                >
                                    2
                                </span>
                                <span class="ml-3 uppercase tracking-[0.3em]">Miembros</span>
                            </div>
                            <div class="mt-2 h-1 rounded-full" :class="!isPlansStep && hasPlans ? 'bg-emerald-500' : 'bg-slate-800'"></div>
                        </button>
                    </li>
                </ol>
            </nav>

            <section class="mt-10">
                <div v-if="isPlansStep" class="rounded-3xl bg-slate-900/60 p-8 shadow-2xl">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">Paso 1</p>
                            <h1 class="text-3xl font-semibold">Planes</h1>
                        </div>
                        <button
                            class="rounded-lg border border-emerald-400 px-4 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-400 hover:text-slate-900"
                            type="button"
                            @click="resetPlanForm"
                        >
                            {{ editingPlanId ? 'Crear nuevo plan' : 'Limpiar formulario' }}
                        </button>
                    </div>
                    <div class="mt-8 grid gap-8 md:grid-cols-[1.2fr,0.8fr]">
                        <div class="rounded-2xl border border-slate-800 p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-semibold">{{ editingPlanId ? 'Editar plan' : 'Agregar plan' }}</h2>
                                <span class="text-xs uppercase tracking-[0.3em] text-slate-500">
                                    {{ plans.length }} configurados
                                </span>
                            </div>
                            <form class="mt-6 space-y-4" @submit.prevent="savePlan">
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="plan-name">Nombre</label>
                                    <input
                                        id="plan-name"
                                        v-model="planForm.name"
                                        type="text"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div class="grid gap-4 md:grid-cols-3">
                                    <div>
                                        <label class="text-sm font-medium text-slate-300" for="plan-tier">Tipo</label>
                                        <select
                                            id="plan-tier"
                                            v-model="planForm.tier"
                                            class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        >
                                            <option v-for="tier in tiers" :key="tier" :value="tier">
                                                {{ tier.toUpperCase() }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-300" for="plan-frequency">Frecuencia</label>
                                        <select
                                            id="plan-frequency"
                                            v-model="planForm.frequency"
                                            class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        >
                                            <option v-for="freq in frequencies" :key="freq" :value="freq">
                                                {{ frequencyLabel(freq) }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-300" for="plan-active">Estado</label>
                                        <select
                                            id="plan-active"
                                            v-model="planForm.is_active"
                                            class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        >
                                            <option :value="true">Activo</option>
                                            <option :value="false">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-3">
                                    <div>
                                        <label class="text-sm font-medium text-slate-300" for="plan-base-price">Precio base</label>
                                        <input
                                            id="plan-base-price"
                                            v-model.number="planForm.base_price"
                                            type="number"
                                            min="0"
                                            class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        />
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-300" for="plan-discount">Descuento (%)</label>
                                        <input
                                            id="plan-discount"
                                            v-model.number="planForm.discount_percent"
                                            type="number"
                                            min="0"
                                            max="100"
                                            class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        />
                                    </div>
                                    <div class="flex flex-col justify-end">
                                        <p class="text-sm text-slate-400">
                                            Precio final estimado:
                                            <span class="font-semibold text-emerald-400">${{ previewFinalPrice }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="plan-description">Descripción</label>
                                    <textarea
                                        id="plan-description"
                                        v-model="planForm.description"
                                        rows="3"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                    ></textarea>
                                </div>
                            <div class="flex flex-wrap gap-3">
                                <button
                                    class="rounded-lg bg-emerald-500 px-6 py-2 font-semibold text-white transition hover:bg-emerald-400 disabled:opacity-50"
                                    :disabled="loadingPlans"
                                    type="submit"
                                >
                                        {{ editingPlanId ? 'Actualizar' : 'Guardar' }}
                                    </button>
                                    <button
                                        v-if="editingPlanId"
                                        class="rounded-lg border border-slate-700 px-6 py-2 text-slate-300 transition hover:border-slate-500"
                                        type="button"
                                        @click="resetPlanForm"
                                    >
                                        Cancelar
                                    </button>
                                </div>
                                <p v-if="planError" class="text-sm text-rose-400">{{ planError }}</p>
                                <p v-if="planSuccess" class="text-sm text-emerald-400">{{ planSuccess }}</p>
                            </form>
                        </div>

                        <div class="rounded-2xl border border-slate-800 p-6">
                            <h2 class="text-xl font-semibold">Resumen de planes</h2>
                            <div class="mt-4 space-y-4">
                                <article
                                    v-for="plan in plans"
                                    :key="plan.id"
                                    class="rounded-xl border border-slate-800 bg-slate-900/60 p-4"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">{{ plan.tier }}</p>
                                            <h3 class="text-lg font-semibold">{{ plan.name }}</h3>
                                        </div>
                                        <span
                                            :class="[
                                                'rounded-full px-3 py-1 text-xs font-semibold',
                                                plan.is_active ? 'bg-emerald-500/10 text-emerald-300' : 'bg-slate-800 text-slate-400',
                                            ]"
                                        >
                                            {{ plan.is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-400">{{ plan.description }}</p>
                                    <div class="mt-4 flex items-center justify-between text-sm">
                                        <div>
                                            <p class="text-slate-500">Frecuencia</p>
                                            <p class="font-semibold">{{ frequencyLabel(plan.frequency) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-500">Precio base</p>
                                            <p class="font-semibold">${{ plan.base_price }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-500">Final</p>
                                            <p class="text-lg font-bold text-emerald-400">${{ plan.final_price }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex gap-3 text-sm">
                                        <button
                                            class="rounded-lg border border-slate-700 px-4 py-2 hover:border-slate-500"
                                            type="button"
                                            @click="editPlan(plan)"
                                        >
                                            Editar
                                        </button>
                                        <button
                                            class="rounded-lg border border-rose-500/40 px-4 py-2 text-rose-300 hover:border-rose-500"
                                            type="button"
                                            @click="deletePlan(plan.id)"
                                        >
                                            Eliminar
                                        </button>
                                    </div>
                                </article>
                                <p v-if="plans.length === 0" class="text-sm text-slate-400">
                                    Aún no has configurado planes. Registra al menos uno para habilitar el módulo de miembros.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <div class="flex-1">
                            <button
                                v-if="!isPlansStep"
                                class="rounded-lg border border-slate-700 px-6 py-2 text-sm font-semibold text-slate-300 transition hover:border-slate-500"
                                type="button"
                                @click="goToPlans"
                            >
                                Regresar
                            </button>
                        </div>
                        <div class="flex-1 flex justify-end">
                            <button
                                class="rounded-lg border border-emerald-400 px-6 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-400 hover:text-slate-900 disabled:opacity-50"
                                type="button"
                                :disabled="!hasPlans"
                                @click="goToMembers"
                            >
                                Siguiente
                            </button>
                        </div>
                    </div>
                    <p v-if="!hasPlans" class="mt-2 text-xs text-slate-400">
                        Configura al menos un plan activo para continuar.
                    </p>
                </div>

                <div v-else class="relative rounded-3xl bg-slate-900/60 p-8 shadow-2xl">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">Paso 2</p>
                            <h1 class="text-3xl font-semibold">Miembros</h1>
                            <p v-if="!hasPlans" class="mt-2 text-sm text-rose-300">
                                Necesitas al menos un plan activo para registrar miembros.
                            </p>
                        </div>
                        <button
                            class="rounded-lg border border-emerald-400 px-4 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-400 hover:text-slate-900 disabled:opacity-50"
                            type="button"
                            :disabled="!hasPlans"
                            @click="resetMemberForm"
                        >
                            {{ editingMemberId ? 'Registrar nuevo miembro' : 'Limpiar formulario' }}
                        </button>
                    </div>

                    <div class="mt-8 space-y-8" :class="{ 'opacity-50 pointer-events-none': !hasPlans }">
                        <div class="rounded-2xl border border-slate-800 p-6">
                            <h2 class="text-xl font-semibold">
                                {{ editingMemberId ? 'Editar miembro' : 'Registrar nuevo miembro' }}
                            </h2>
                            <form class="mt-6 grid gap-4 md:grid-cols-2" @submit.prevent="saveMember">
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-name">Nombre</label>
                                    <input
                                        id="member-name"
                                        v-model="memberForm.name"
                                        type="text"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-last-name">Apellidos</label>
                                    <input
                                        id="member-last-name"
                                        v-model="memberForm.last_name"
                                        type="text"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-id">Identificación</label>
                                    <input
                                        id="member-id"
                                        v-model="memberForm.identification_number"
                                        type="text"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-email">Correo</label>
                                    <input
                                        id="member-email"
                                        v-model="memberForm.email"
                                        type="email"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-phone">Teléfono</label>
                                    <input
                                        id="member-phone"
                                        v-model="memberForm.phone"
                                        type="text"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-plan">Plan</label>
                                    <select
                                        id="member-plan"
                                        v-model.number="memberForm.plan_id"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    >
                                        <option value="" disabled>Selecciona un plan</option>
                                        <option v-for="plan in plans" :key="plan.id" :value="plan.id">
                                            {{ plan.name }} - ${{ plan.final_price }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-start">Inicio membresía</label>
                                    <input
                                        id="member-start"
                                        v-model="memberForm.membership_start_date"
                                        type="date"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-status">Estado</label>
                                    <select
                                        id="member-status"
                                        v-model="memberForm.status"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    >
                                        <option v-for="status in statuses" :key="status" :value="status">
                                            {{ statusLabel(status) }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-emergency-name">Contacto de emergencia</label>
                                    <input
                                        id="member-emergency-name"
                                        v-model="memberForm.emergency_contact_name"
                                        type="text"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-300" for="member-emergency-phone">Teléfono emergencia</label>
                                    <input
                                        id="member-emergency-phone"
                                        v-model="memberForm.emergency_contact_phone"
                                        type="text"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                        required
                                    />
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-slate-300" for="member-notes">Notas</label>
                                    <textarea
                                        id="member-notes"
                                        v-model="memberForm.notes"
                                        rows="3"
                                        class="mt-1 w-full rounded-lg border border-slate-700 bg-transparent px-4 py-2 focus:border-emerald-400 focus:outline-none"
                                    ></textarea>
                                </div>
                                <div class="md:col-span-2 flex gap-3">
                                    <button
                                        class="rounded-lg bg-emerald-500 px-6 py-2 font-semibold text-white transition hover:bg-emerald-400 disabled:opacity-50"
                                        :disabled="loadingMembers || !hasPlans"
                                        type="submit"
                                    >
                                        {{ editingMemberId ? 'Actualizar' : 'Registrar' }}
                                    </button>
                                    <button
                                        v-if="editingMemberId"
                                        class="rounded-lg border border-slate-700 px-6 py-2 text-slate-300 transition hover:border-slate-500"
                                        type="button"
                                        @click="resetMemberForm"
                                    >
                                        Cancelar
                                    </button>
                                </div>
                                <p v-if="memberError" class="md:col-span-2 text-sm text-rose-400">{{ memberError }}</p>
                                <p v-if="memberSuccess" class="md:col-span-2 text-sm text-emerald-400">{{ memberSuccess }}</p>
                            </form>
                        </div>

                        <div class="rounded-2xl border border-slate-800 p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-semibold">Lista de miembros</h2>
                                <span class="text-xs uppercase tracking-[0.3em] text-slate-500">
                                    {{ members.length }} inscritos
                                </span>
                            </div>
                            <div class="mt-6 overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead>
                                        <tr class="text-slate-400">
                                            <th class="px-3 py-2 font-medium">Miembro</th>
                                            <th class="px-3 py-2 font-medium">Plan</th>
                                            <th class="px-3 py-2 font-medium">Contacto</th>
                                            <th class="px-3 py-2 font-medium">Estado</th>
                                            <th class="px-3 py-2 font-medium">Periodo</th>
                                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="member in members"
                                            :key="member.id"
                                            :class="[
                                                'border-t border-slate-800',
                                                isExpired(member.current_period_end_date)
                                                    ? 'bg-rose-950/40 text-rose-200'
                                                    : 'text-slate-200',
                                            ]"
                                        >
                                            <td class="px-3 py-3">
                                                <div class="font-semibold">{{ member.name }} {{ member.last_name }}</div>
                                                <div class="text-xs text-slate-400">{{ member.identification_number }}</div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div>{{ member.plan?.name ?? 'Sin plan' }}</div>
                                                <div class="text-xs text-slate-400">{{ frequencyLabel(member.plan?.frequency) }}</div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div>{{ member.email }}</div>
                                                <div class="text-xs text-slate-400">{{ member.phone }}</div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span :class="statusBadge(member.status)">
                                                    {{ statusLabel(member.status) }}
                                                </span>
                                                <div
                                                    v-if="isExpired(member.current_period_end_date)"
                                                    class="mt-2 text-xs font-semibold text-rose-300"
                                                >
                                                    Membresía vencida
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="text-xs text-slate-400">Inicio</div>
                                                <div>{{ formatDate(member.membership_start_date) }}</div>
                                                <div class="text-xs text-slate-400">Vence</div>
                                                <div>{{ formatDate(member.current_period_end_date) }}</div>
                                            </td>
                                            <td class="px-3 py-3 text-right space-x-2">
                                                <button
                                                    class="rounded border border-slate-700 px-3 py-1 text-xs hover:border-slate-500"
                                                    type="button"
                                                    @click="editMember(member)"
                                                >
                                                    Editar
                                                </button>
                                                <button
                                                    class="rounded border border-rose-500/30 px-3 py-1 text-xs text-rose-300 hover:border-rose-500"
                                                    type="button"
                                                    @click="deleteMember(member.id)"
                                                >
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p v-if="members.length === 0" class="mt-4 text-sm text-slate-400">
                                    Aún no hay miembros registrados.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <div class="flex-1">
                            <button
                                class="rounded-lg border border-slate-700 px-6 py-2 text-sm font-semibold text-slate-300 transition hover:border-slate-500"
                                type="button"
                                @click="goToPlans"
                            >
                                Regresar
                            </button>
                        </div>
                        <div class="flex-1 flex justify-end">
                            <button
                                v-if="isPlansStep"
                                class="rounded-lg border border-emerald-400 px-6 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-400 hover:text-slate-900 disabled:opacity-50"
                                type="button"
                                disabled
                            >
                                Siguiente
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { computed, reactive, ref, watch } from 'vue';

import Navbar from '@/components/Navbar.vue';

interface Plan {
    id: number;
    name: string;
    tier: string;
    frequency: string;
    base_price: number;
    discount_percent: number;
    final_price: number;
    description: string | null;
    is_active: boolean;
}

interface Member {
    id: number;
    plan_id: number;
    name: string;
    last_name: string;
    identification_number: string;
    email: string;
    phone: string;
    emergency_contact_name: string;
    emergency_contact_phone: string;
    membership_start_date: string;
    current_period_end_date: string;
    status: string;
    notes: string | null;
    plan?: Plan;
}

const isPlansStep = ref(true);

const plans = ref<Plan[]>([]);
const members = ref<Member[]>([]);

const loadingPlans = ref(false);
const loadingMembers = ref(false);

const planError = ref('');
const planSuccess = ref('');
const memberError = ref('');
const memberSuccess = ref('');

const editingPlanId = ref<number | null>(null);
const editingMemberId = ref<number | null>(null);

const planForm = reactive({
    name: '',
    tier: 'normal',
    frequency: 'monthly',
    base_price: 300,
    discount_percent: 0,
    description: '',
    is_active: true,
});

const memberForm = reactive({
    plan_id: '' as number | '' | null,
    name: '',
    last_name: '',
    identification_number: '',
    email: '',
    phone: '',
    emergency_contact_name: '',
    emergency_contact_phone: '',
    membership_start_date: new Date().toISOString().substring(0, 10),
    status: 'trial',
    notes: '',
});

const tiers = ['normal', 'plus', 'ultra'];
const frequencies = ['monthly', 'semiannual', 'annual'];
const statuses = ['trial', 'active', 'suspended', 'inactive'];

const hasPlans = computed(() => plans.value.length > 0);

watch(hasPlans, (value) => {
    if (!value) {
        isPlansStep.value = true;
    }
});

const previewFinalPrice = computed(() => {
    const base = planForm.base_price ?? 0;
    const discount = planForm.discount_percent ?? 0;
    return Math.max(Math.round(base * (1 - discount / 100)), 0);
});

const frequencyLabel = (value?: string): string => {
    switch (value) {
        case 'semiannual':
            return 'Semestral';
        case 'annual':
            return 'Anual';
        default:
            return 'Mensual';
    }
};

const statusLabel = (status: string): string => {
    switch (status) {
        case 'active':
            return 'Activo';
        case 'inactive':
            return 'Inactivo';
        case 'suspended':
            return 'Suspendido';
        default:
            return 'Prueba';
    }
};

const statusBadge = (status: string): string[] => {
    const base = 'inline-flex rounded-full px-3 py-1 text-xs font-semibold';
    const palette = {
        active: 'bg-emerald-500/10 text-emerald-300',
        inactive: 'bg-slate-800 text-slate-400',
        suspended: 'bg-amber-500/10 text-amber-300',
        trial: 'bg-blue-500/10 text-blue-300',
    } as const;

    return [base, palette[status as keyof typeof palette] ?? 'bg-slate-800 text-slate-400'];
};

const formatDate = (value?: string | null): string => {
    if (!value) {
        return '—';
    }
    return new Date(value).toLocaleDateString();
};

const isExpired = (value?: string | null): boolean => {
    if (!value) {
        return false;
    }
    const today = new Date();
    const target = new Date(value);
    return target.getTime() < today.setHours(0, 0, 0, 0);
};

const goToMembers = (): void => {
    if (!hasPlans.value) {
        return;
    }
    isPlansStep.value = false;
};

const goToPlans = (): void => {
    isPlansStep.value = true;
};

const fetchPlans = async (): Promise<void> => {
    loadingPlans.value = true;
    planError.value = '';

    try {
        const { data } = await axios.get<Plan[]>('/api/admin/plans');
        plans.value = data;
        if (!memberForm.plan_id && data.length > 0) {
            memberForm.plan_id = data[0].id;
        }
    } catch (error) {
        console.error(error);
        planError.value = 'No pudimos cargar los planes.';
    } finally {
        loadingPlans.value = false;
    }
};

const fetchMembers = async (): Promise<void> => {
    loadingMembers.value = true;
    memberError.value = '';

    try {
        const { data } = await axios.get<Member[]>('/api/admin/members');
        members.value = data;
    } catch (error) {
        console.error(error);
        memberError.value = 'No pudimos cargar los miembros.';
    } finally {
        loadingMembers.value = false;
    }
};

const resetPlanForm = (): void => {
    editingPlanId.value = null;
    planForm.name = '';
    planForm.tier = 'normal';
    planForm.frequency = 'monthly';
    planForm.base_price = 300;
    planForm.discount_percent = 0;
    planForm.description = '';
    planForm.is_active = true;
    planError.value = '';
    planSuccess.value = '';
};

const editPlan = (plan: Plan): void => {
    editingPlanId.value = plan.id;
    planForm.name = plan.name;
    planForm.tier = plan.tier;
    planForm.frequency = plan.frequency;
    planForm.base_price = plan.base_price;
    planForm.discount_percent = plan.discount_percent;
    planForm.description = plan.description ?? '';
    planForm.is_active = plan.is_active;
    planSuccess.value = '';
    planError.value = '';
};

const savePlan = async (): Promise<void> => {
    planError.value = '';
    planSuccess.value = '';
    try {
        if (editingPlanId.value) {
            await axios.put(`/api/admin/plans/${editingPlanId.value}`, planForm);
            planSuccess.value = 'Plan actualizado correctamente.';
        } else {
            await axios.post('/api/admin/plans', planForm);
            planSuccess.value = 'Plan creado correctamente.';
        }
        await fetchPlans();
        resetPlanForm();
    } catch (error) {
        console.error(error);
        planError.value = 'Error al guardar el plan. Verifica los datos.';
    }
};

const deletePlan = async (planId: number): Promise<void> => {
    if (!confirm('¿Eliminar este plan?')) {
        return;
    }

    try {
        await axios.delete(`/api/admin/plans/${planId}`);
        await fetchPlans();
        await fetchMembers();
    } catch (error) {
        console.error(error);
        planError.value = 'No pudimos eliminar el plan.';
    }
};

const resetMemberForm = (): void => {
    editingMemberId.value = null;
    memberForm.plan_id = plans.value[0]?.id ?? '';
    memberForm.name = '';
    memberForm.last_name = '';
    memberForm.identification_number = '';
    memberForm.email = '';
    memberForm.phone = '';
    memberForm.emergency_contact_name = '';
    memberForm.emergency_contact_phone = '';
    memberForm.membership_start_date = new Date().toISOString().substring(0, 10);
    memberForm.status = 'trial';
    memberForm.notes = '';
    memberError.value = '';
    memberSuccess.value = '';
};

const editMember = (member: Member): void => {
    editingMemberId.value = member.id;
    memberForm.plan_id = member.plan_id;
    memberForm.name = member.name;
    memberForm.last_name = member.last_name;
    memberForm.identification_number = member.identification_number;
    memberForm.email = member.email;
    memberForm.phone = member.phone;
    memberForm.emergency_contact_name = member.emergency_contact_name;
    memberForm.emergency_contact_phone = member.emergency_contact_phone;
    memberForm.membership_start_date = member.membership_start_date;
    memberForm.status = member.status;
    memberForm.notes = member.notes ?? '';
    memberSuccess.value = '';
    memberError.value = '';
};

const saveMember = async (): Promise<void> => {
    if (!memberForm.plan_id) {
        memberError.value = 'Selecciona un plan válido.';
        return;
    }

    memberError.value = '';
    memberSuccess.value = '';

    try {
        if (editingMemberId.value) {
            await axios.put(`/api/admin/members/${editingMemberId.value}`, memberForm);
            memberSuccess.value = 'Miembro actualizado.';
        } else {
            await axios.post('/api/admin/members', memberForm);
            memberSuccess.value = 'Miembro registrado.';
        }

        await fetchMembers();
        resetMemberForm();
    } catch (error) {
        console.error(error);
        memberError.value = 'No pudimos guardar al miembro. Verifica la información.';
    }
};

const deleteMember = async (memberId: number): Promise<void> => {
    if (!confirm('¿Eliminar este registro?')) {
        return;
    }

    try {
        await axios.delete(`/api/admin/members/${memberId}`);
        await fetchMembers();
    } catch (error) {
        console.error(error);
        memberError.value = 'No pudimos eliminar al miembro.';
    }
};

fetchPlans();
fetchMembers();
</script>
