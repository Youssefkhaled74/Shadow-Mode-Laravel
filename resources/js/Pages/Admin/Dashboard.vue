<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import StatsCard from '@/Components/Shadow/StatsCard.vue';

const props = defineProps({
    metrics: Object,
    recentSessions: Array,
});

const cards = [
    { title: 'Users', value: props.metrics.users },
    { title: 'Coaches', value: props.metrics.coaches },
    { title: 'Active Rooms', value: props.metrics.active_rooms },
    { title: 'Avg Platform Score', value: `${props.metrics.avg_platform_score}/100` },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout title="Admin Dashboard">
        <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <StatsCard v-for="card in cards" :key="card.title" :title="card.title" :value="card.value" />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="glass p-5">
                    <h3 class="mb-4 text-sm uppercase tracking-[0.18em] text-muted">Session State Distribution</h3>
                    <div class="space-y-2">
                        <div v-for="(count, stateName) in metrics.state_distribution" :key="stateName" class="flex items-center justify-between rounded-lg border border-shell/70 bg-shell/20 px-3 py-2 text-sm">
                            <span class="capitalize text-shell">{{ stateName }}</span>
                            <span class="text-muted">{{ count }}</span>
                        </div>
                    </div>
                </div>

                <div class="glass p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm uppercase tracking-[0.18em] text-muted">Moderation</h3>
                        <div class="flex gap-2">
                            <Link :href="route('admin.users.index')" class="text-xs text-brand-300">Users</Link>
                            <Link :href="route('admin.sessions.index')" class="text-xs text-brand-300">Sessions</Link>
                        </div>
                    </div>
                    <p class="text-sm text-muted">Use user and session admin tables to edit roles, audit activity, and archive problematic sessions.</p>
                </div>
            </div>

            <div class="glass p-5">
                <h3 class="mb-4 text-sm uppercase tracking-[0.18em] text-muted">Recent Sessions</h3>
                <div class="space-y-2">
                    <div v-for="session in recentSessions" :key="session.id" class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                        <p class="font-semibold text-shell">{{ session.title }}</p>
                        <p class="text-xs text-muted">{{ session.state }} · {{ session.scenario_type }} · Host: {{ session.host?.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

