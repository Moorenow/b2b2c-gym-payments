<script setup>
import { ref } from 'vue';

const message =ref('Bienvenido a gym payments');
</script>

<template>
    <main class="min-h-dvh bg-slate-950 text-slate-50 flex flex-col items-center justify-center gap-6">
        <div class="text-center space-y-2">
            <h1 class="text-4xl md:text-5xl font-semibold">{{ message }}</h1>
        </div>

        <div class="flex flex-wrap justify-center gap-4 text-sm uppercase tracking-[0.3em] text-slate-300">
            <span>Laravel</span>
            <span>Vue</span>
        </div>
    </main>
</template>
