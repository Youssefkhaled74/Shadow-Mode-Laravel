<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatsCard from '@/Components/Shadow/StatsCard.vue';
import TrendChart from '@/Components/Shadow/TrendChart.vue';
import ActivityList from '@/Components/Shadow/ActivityList.vue';

const props = defineProps({
    summary: Object,
});

const cards = [
    { title: 'Total Sessions', value: props.summary.cards.total_sessions, subtitle: 'All completed and active drills' },
    { title: 'Average Score', value: `${props.summary.cards.average_score}/100`, subtitle: 'Rolling cross-session average' },
    { title: 'Live Rooms', value: props.summary.cards.live_rooms, subtitle: 'Currently active simulations' },
    { title: 'Reports', value: props.summary.cards.reports_generated, subtitle: 'Generated coaching reports' },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout title="Dashboard">
        <div class="space-y-6">
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <StatsCard v-for="card in cards" :key="card.title" :title="card.title" :value="card.value" :subtitle="card.subtitle" />
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <TrendChart :points="summary.trend" />

                <div class="glass p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-muted">Upcoming Sessions</h3>
                        <Link :href="route('sessions.create')" class="text-xs text-brand-300">Create new</Link>
                    </div>

                    <div v-if="summary.upcoming.length" class="space-y-3">
                        <div v-for="session in summary.upcoming" :key="session.id" class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                            <p class="text-sm font-semibold text-shell">{{ session.title }}</p>
                            <p class="mt-1 text-xs text-muted">{{ session.scenario_type }} · {{ session.state }}</p>
                            <Link :href="route('rooms.show', session.uuid)" class="mt-2 inline-flex text-xs text-brand-300">Open room</Link>
                        </div>
                    </div>

                    <div v-else class="rounded-xl border border-dashed border-shell/70 p-6 text-center text-sm text-muted">
                        No upcoming sessions. Schedule your next training block.
                    </div>
                </div>
            </section>

            <section>
                <ActivityList :items="summary.recent_activity" />
            </section>
        </div>
    </AuthenticatedLayout>
</template>

