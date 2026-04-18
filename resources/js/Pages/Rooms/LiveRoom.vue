<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useRoomRealtime } from '@/Composables/useRoomRealtime';

const props = defineProps({
    session: Object,
});
const ziggyRoute = (...args) => window.route(...args);

const { members, activity, hints, latestMetrics, state } = useRoomRealtime(props.session.uuid);
const isSendingHint = ref(false);
const isPushingMetric = ref(false);

const stateForm = useForm({ state: props.session.state });
const hintForm = useForm({
    category: 'general',
    severity: 'medium',
    content: '',
    target_user_id: null,
});
const metricForm = useForm({
    confidence_score: 70,
    clarity_score: 70,
    pace_score: 70,
    overall_score: 70,
    filler_word_count: 1,
    missed_question_count: 0,
});

const effectiveState = computed(() => state.value ?? props.session.state);
const displayedHints = computed(() => (hints.value.length ? hints.value : props.session.hints));
const displayedActivity = computed(() => (activity.value.length ? activity.value : props.session.events));
const metricSnapshot = computed(() => latestMetrics.value?.snapshot ?? props.session.metrics?.[0] ?? null);

function changeState(next) {
    stateForm.state = next;
    stateForm.patch(ziggyRoute('sessions.state.update', props.session.uuid), {
        preserveScroll: true,
    });
}

async function sendHint() {
    hintForm.clearErrors();
    isSendingHint.value = true;

    try {
        const response = await window.axios.post(
            ziggyRoute('rooms.hints.store', props.session.uuid),
            hintForm.data(),
        );

        if (response.data?.hint) {
            hints.value = [response.data.hint, ...hints.value].slice(0, 30);
        }

        hintForm.reset('content');
    } catch (error) {
        if (error.response?.status === 422 && error.response.data?.errors) {
            hintForm.setError(error.response.data.errors);
            return;
        }

        hintForm.setError('content', 'Unable to send hint right now. Please try again.');
    } finally {
        isSendingHint.value = false;
    }
}

async function pushMetric() {
    metricForm.clearErrors();
    isPushingMetric.value = true;

    try {
        const response = await window.axios.post(
            ziggyRoute('rooms.metrics.store', props.session.uuid),
            metricForm.data(),
        );

        if (response.data?.snapshot) {
            latestMetrics.value = {
                snapshot: response.data.snapshot,
            };
        }
    } catch (error) {
        if (error.response?.status === 422 && error.response.data?.errors) {
            metricForm.setError(error.response.data.errors);
            return;
        }

        metricForm.setError('overall_score', 'Unable to push metric right now. Please try again.');
    } finally {
        isPushingMetric.value = false;
    }
}
</script>

<template>
    <Head :title="`${session.title} · Live Room`" />

    <AuthenticatedLayout title="Live Room">
        <div class="space-y-6">
            <section class="glass p-5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-muted">Room {{ session.room_code }}</p>
                        <h2 class="mt-1 text-2xl font-semibold text-shell">{{ session.title }}</h2>
                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-brand-300">Status: {{ effectiveState }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button class="rounded-lg border border-shell/70 px-3 py-2 text-xs" @click="changeState('waiting')">Waiting</button>
                        <button class="rounded-lg border border-shell/70 px-3 py-2 text-xs" @click="changeState('live')">Go Live</button>
                        <button class="rounded-lg border border-shell/70 px-3 py-2 text-xs" @click="changeState('paused')">Pause</button>
                        <button class="rounded-lg border border-shell/70 px-3 py-2 text-xs" @click="changeState('ended')">End</button>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-6">
                    <div class="glass p-5">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm uppercase tracking-[0.18em] text-muted">Live Scoring Pulse</h3>
                            <button :disabled="isPushingMetric" class="rounded-lg bg-gradient-to-r from-brand-500 to-cyan-500 px-3 py-1.5 text-xs font-semibold text-white disabled:opacity-60" @click="pushMetric">Push Sample Metric</button>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-3">
                            <div class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                                <p class="text-xs uppercase tracking-[0.14em] text-muted">Confidence</p>
                                <p class="mt-1 text-2xl font-semibold text-shell">{{ metricSnapshot?.confidence_score ?? '--' }}</p>
                            </div>
                            <div class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                                <p class="text-xs uppercase tracking-[0.14em] text-muted">Clarity</p>
                                <p class="mt-1 text-2xl font-semibold text-shell">{{ metricSnapshot?.clarity_score ?? '--' }}</p>
                            </div>
                            <div class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                                <p class="text-xs uppercase tracking-[0.14em] text-muted">Pace</p>
                                <p class="mt-1 text-2xl font-semibold text-shell">{{ metricSnapshot?.pace_score ?? '--' }}</p>
                            </div>
                        </div>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                                <p class="text-xs uppercase tracking-[0.14em] text-muted">Filler Warnings</p>
                                <p class="mt-1 text-xl font-semibold text-shell">{{ metricSnapshot?.filler_word_count ?? 0 }}</p>
                            </div>
                            <div class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                                <p class="text-xs uppercase tracking-[0.14em] text-muted">Missed Questions</p>
                                <p class="mt-1 text-xl font-semibold text-shell">{{ metricSnapshot?.missed_question_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass p-5">
                        <h3 class="mb-4 text-sm uppercase tracking-[0.18em] text-muted">Session Activity Stream</h3>
                        <div class="max-h-80 space-y-3 overflow-y-auto pr-1">
                            <div v-for="item in displayedActivity" :key="item.id" class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                                <p class="text-sm font-semibold text-shell">{{ item.title }}</p>
                                <p class="mt-1 text-xs text-muted">{{ item.message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="glass p-5">
                        <h3 class="mb-4 text-sm uppercase tracking-[0.18em] text-muted">Presence</h3>
                        <div class="space-y-2">
                            <div v-for="member in (members.length ? members : session.participants.map((participant) => ({ id: participant.user.id, name: participant.user.name, role: participant.role })))" :key="member.id" class="flex items-center justify-between rounded-xl border border-shell/70 bg-shell/20 px-3 py-2">
                                <p class="text-sm font-semibold text-shell">{{ member.name }}</p>
                                <p class="text-[11px] uppercase tracking-[0.14em] text-muted">{{ member.role }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass p-5">
                        <h3 class="mb-4 text-sm uppercase tracking-[0.18em] text-muted">Coaching Panel</h3>
                        <form class="space-y-3" @submit.prevent="sendHint">
                            <div class="grid gap-3 sm:grid-cols-2">
                                <select v-model="hintForm.category" class="rounded-xl border border-shell/70 bg-shell/20 px-3 py-2 text-xs text-shell">
                                    <option value="general">General</option>
                                    <option value="confidence">Confidence</option>
                                    <option value="clarity">Clarity</option>
                                    <option value="pace">Pace</option>
                                    <option value="filler">Filler</option>
                                    <option value="question">Question</option>
                                    <option value="follow_up">Follow-up</option>
                                </select>
                                <select v-model="hintForm.severity" class="rounded-xl border border-shell/70 bg-shell/20 px-3 py-2 text-xs text-shell">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <textarea v-model="hintForm.content" rows="3" class="w-full rounded-xl border border-shell/70 bg-shell/20 px-3 py-2 text-sm text-shell" placeholder="Drop instant coaching hint"></textarea>
                            <button :disabled="isSendingHint" class="w-full rounded-xl bg-gradient-to-r from-brand-500 to-cyan-500 px-4 py-2 text-xs font-semibold text-white disabled:opacity-60">Send Hint</button>
                        </form>

                        <div class="mt-4 max-h-48 space-y-2 overflow-y-auto">
                            <div v-for="hint in displayedHints" :key="hint.id" class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                                <p class="text-xs uppercase tracking-[0.14em] text-brand-300">{{ hint.category }} · {{ hint.severity }}</p>
                                <p class="mt-1 text-sm text-shell">{{ hint.content }}</p>
                            </div>
                        </div>
                    </div>

                    <Link :href="route('reports.show', session.uuid)" class="block rounded-xl border border-shell/70 bg-panel/80 px-4 py-3 text-center text-sm text-brand-300">
                        Open Session Summary
                    </Link>
                </aside>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

