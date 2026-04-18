<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    sessions: Object,
});
</script>

<template>
    <Head title="Sessions" />

    <AuthenticatedLayout title="Sessions">
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-muted">Manage room lifecycle, state, and live participation.</p>
                <div class="flex gap-2">
                    <Link :href="route('sessions.join')" class="rounded-lg border border-shell/70 px-3 py-2 text-xs text-muted">Join by code</Link>
                    <Link :href="route('sessions.create')" class="rounded-lg bg-gradient-to-r from-brand-500 to-cyan-500 px-3 py-2 text-xs font-semibold text-white">Create session</Link>
                </div>
            </div>

            <div class="glass overflow-hidden">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-shell/70 bg-shell/30 text-xs uppercase tracking-[0.16em] text-muted">
                        <tr>
                            <th class="px-4 py-3">Session</th>
                            <th class="px-4 py-3">State</th>
                            <th class="px-4 py-3">Participants</th>
                            <th class="px-4 py-3">Average Score</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="session in sessions.data" :key="session.uuid" class="border-b border-shell/60 last:border-b-0">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-shell">{{ session.title }}</p>
                                <p class="text-xs text-muted">{{ session.scenario_type }} · #{{ session.room_code }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-brand-500/20 px-2.5 py-1 text-xs uppercase tracking-[0.14em] text-brand-100">{{ session.state }}</span>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ session.participants_count }}</td>
                            <td class="px-4 py-3 text-muted">{{ session.average_score }}</td>
                            <td class="px-4 py-3">
                                <Link :href="route('rooms.show', session.uuid)" class="text-xs text-brand-300">Open room</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

