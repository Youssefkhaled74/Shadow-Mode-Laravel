<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    scenario_type: 'interview',
    description: '',
    scheduled_for: '',
});

const ziggyRoute = (...args) => window.route(...args);

function submit() {
    form.post(ziggyRoute('sessions.store'));
}
</script>

<template>
    <Head title="Create Session" />

    <AuthenticatedLayout title="Create Session">
        <div class="mx-auto max-w-3xl">
            <form @submit.prevent="submit" class="glass space-y-5 p-6">
                <div>
                    <label class="text-xs uppercase tracking-[0.18em] text-muted">Session Title</label>
                    <input v-model="form.title" class="mt-2 w-full rounded-xl border border-shell/70 bg-shell/20 px-4 py-3 text-sm text-shell" placeholder="Mock Behavioral Interview" />
                    <p v-if="form.errors.title" class="mt-1 text-xs text-rose-400">{{ form.errors.title }}</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs uppercase tracking-[0.18em] text-muted">Scenario</label>
                        <select v-model="form.scenario_type" class="mt-2 w-full rounded-xl border border-shell/70 bg-shell/20 px-4 py-3 text-sm text-shell">
                            <option value="interview">Interview</option>
                            <option value="sales">Sales</option>
                            <option value="negotiation">Negotiation</option>
                            <option value="communication">Communication</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-[0.18em] text-muted">Schedule (optional)</label>
                        <input type="datetime-local" v-model="form.scheduled_for" class="mt-2 w-full rounded-xl border border-shell/70 bg-shell/20 px-4 py-3 text-sm text-shell" />
                    </div>
                </div>

                <div>
                    <label class="text-xs uppercase tracking-[0.18em] text-muted">Session Prompt</label>
                    <textarea v-model="form.description" rows="5" class="mt-2 w-full rounded-xl border border-shell/70 bg-shell/20 px-4 py-3 text-sm text-shell" placeholder="Define scenario context, constraints, and success criteria."></textarea>
                </div>

                <div class="flex justify-end">
                    <button :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-brand-500 to-cyan-500 px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
                        {{ form.processing ? 'Creating...' : 'Create Live Room' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

