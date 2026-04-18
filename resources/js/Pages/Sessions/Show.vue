<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    session: Object,
});
</script>

<template>
    <Head :title="session.title" />

    <AuthenticatedLayout :title="session.title">
        <div class="space-y-6">
            <div class="glass p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-muted">{{ session.scenario_type }} · #{{ session.room_code }}</p>
                        <h2 class="mt-2 text-2xl font-semibold text-shell">{{ session.title }}</h2>
                        <p class="mt-3 max-w-2xl text-sm text-muted">{{ session.description }}</p>
                    </div>
                    <Link :href="route('rooms.show', session.uuid)" class="rounded-lg bg-gradient-to-r from-brand-500 to-cyan-500 px-4 py-2 text-xs font-semibold text-white">
                        Open Live Room
                    </Link>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="glass p-5">
                    <h3 class="mb-3 text-sm uppercase tracking-[0.16em] text-muted">Participants</h3>
                    <div class="space-y-2">
                        <div v-for="participant in session.participants" :key="participant.id" class="rounded-xl border border-shell/70 bg-shell/20 px-3 py-2 text-sm">
                            <p class="font-semibold text-shell">{{ participant.user.name }}</p>
                            <p class="text-xs uppercase tracking-[0.14em] text-muted">{{ participant.role }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass p-5">
                    <h3 class="mb-3 text-sm uppercase tracking-[0.16em] text-muted">Recent Activity</h3>
                    <div class="space-y-2">
                        <div v-for="activity in session.events" :key="activity.id" class="rounded-xl border border-shell/70 bg-shell/20 px-3 py-2 text-sm">
                            <p class="font-semibold text-shell">{{ activity.title }}</p>
                            <p class="text-xs text-muted">{{ activity.message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

