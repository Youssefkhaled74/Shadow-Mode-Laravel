<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    session: Object,
    report: Object,
});
</script>

<template>
    <Head :title="`${session.title} Report`" />

    <AuthenticatedLayout title="Session Report">
        <div class="space-y-6">
            <div class="glass p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-muted">Post-session summary</p>
                <h2 class="mt-2 text-2xl font-semibold text-shell">{{ session.title }}</h2>
                <p class="mt-2 text-sm text-muted">{{ report.summary }}</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-4">
                <div class="glass p-4">
                    <p class="text-xs uppercase tracking-[0.14em] text-muted">Overall</p>
                    <p class="mt-1 text-3xl font-semibold text-shell">{{ report.overall_score }}</p>
                </div>
                <div class="glass p-4" v-for="(value, key) in report.score_breakdown" :key="key">
                    <p class="text-xs uppercase tracking-[0.14em] text-muted">{{ key }}</p>
                    <p class="mt-1 text-3xl font-semibold text-shell">{{ value }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="glass p-5">
                    <h3 class="text-sm uppercase tracking-[0.18em] text-muted">Best Response</h3>
                    <p class="mt-3 text-sm text-shell">{{ report.best_response }}</p>
                </div>
                <div class="glass p-5">
                    <h3 class="text-sm uppercase tracking-[0.18em] text-muted">Weakest Response</h3>
                    <p class="mt-3 text-sm text-shell">{{ report.weakest_response }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="glass p-5">
                    <h3 class="text-sm uppercase tracking-[0.18em] text-muted">Key Mistakes</h3>
                    <ul class="mt-3 space-y-2 text-sm text-shell">
                        <li v-for="(mistake, idx) in report.key_mistakes" :key="idx" class="rounded-lg border border-shell/70 bg-shell/20 px-3 py-2">{{ mistake }}</li>
                    </ul>
                </div>
                <div class="glass p-5">
                    <h3 class="text-sm uppercase tracking-[0.18em] text-muted">Improvement Suggestions</h3>
                    <ul class="mt-3 space-y-2 text-sm text-shell">
                        <li v-for="(suggestion, idx) in report.improvement_suggestions" :key="idx" class="rounded-lg border border-shell/70 bg-shell/20 px-3 py-2">{{ suggestion }}</li>
                    </ul>
                </div>
            </div>

            <div class="glass p-5">
                <h3 class="mb-4 text-sm uppercase tracking-[0.18em] text-muted">Timeline Replay</h3>
                <div class="space-y-2">
                    <div v-for="moment in report.timeline_moments" :key="moment.id" class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-shell">{{ moment.title }}</p>
                            <span class="text-xs text-muted">{{ moment.timestamp_seconds }}s</span>
                        </div>
                        <p class="mt-1 text-xs text-muted">{{ moment.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

