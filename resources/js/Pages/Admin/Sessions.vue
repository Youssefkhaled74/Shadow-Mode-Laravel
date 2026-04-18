<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    sessions: Object,
});
const ziggyRoute = (...args) => window.route(...args);

function archive(uuid) {
    if (!confirm('Archive this session?')) {
        return;
    }

    router.delete(ziggyRoute('admin.sessions.destroy', uuid), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Admin Sessions" />

    <AuthenticatedLayout title="Session Moderation">
        <div class="glass overflow-hidden">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-shell/70 bg-shell/20 text-xs uppercase tracking-[0.16em] text-muted">
                    <tr>
                        <th class="px-4 py-3">Session</th>
                        <th class="px-4 py-3">State</th>
                        <th class="px-4 py-3">Host</th>
                        <th class="px-4 py-3">Participants</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="session in sessions.data" :key="session.uuid" class="border-b border-shell/60 last:border-b-0">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-shell">{{ session.title }}</p>
                            <p class="text-xs text-muted">{{ session.scenario_type }}</p>
                        </td>
                        <td class="px-4 py-3 text-muted">{{ session.state }}</td>
                        <td class="px-4 py-3 text-muted">{{ session.host?.name }}</td>
                        <td class="px-4 py-3 text-muted">{{ session.participants_count }}</td>
                        <td class="px-4 py-3"><button class="text-xs text-rose-400" @click="archive(session.uuid)">Archive</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>

