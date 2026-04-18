<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen bg-shell text-shell">
        <Head :title="title" />

        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute left-10 top-10 h-48 w-48 rounded-full bg-brand-500/20 blur-3xl"></div>
            <div class="absolute bottom-10 right-10 h-56 w-56 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="grid-overlay absolute inset-0 opacity-30"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl flex-col px-4 py-8 sm:px-6 lg:px-8">
            <header class="mb-8 flex items-center justify-between">
                <Link :href="route('home')" class="flex items-center gap-3">
                    <img src="/images/shadow-mode-logo.png" alt="Shadow Mode" class="h-14 w-auto rounded-md object-contain" />
                </Link>
                <Link :href="route('home')" class="text-sm text-muted hover:text-shell">Back to Home</Link>
            </header>

            <main class="flex flex-1 items-center justify-center">
                <div class="w-full max-w-md rounded-2xl border border-shell/60 bg-panel/80 p-7 shadow-2xl shadow-brand-900/20 backdrop-blur-xl">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Access Shadow Mode',
        },
    },
};
</script>

