<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    room_code: '',
});

const ziggyRoute = (...args) => window.route(...args);

function submit() {
    form.post(ziggyRoute('sessions.join.submit'));
}
</script>

<template>
    <Head title="Join Session" />

    <AuthenticatedLayout title="Join Session">
        <div class="mx-auto max-w-xl">
            <form @submit.prevent="submit" class="glass space-y-5 p-6">
                <p class="text-sm text-muted">Join a live simulation using the room code.</p>

                <div>
                    <label class="text-xs uppercase tracking-[0.18em] text-muted">Room Code</label>
                    <input v-model="form.room_code" maxlength="8" class="mt-2 w-full rounded-xl border border-shell/70 bg-shell/20 px-4 py-3 text-sm uppercase tracking-[0.2em] text-shell" placeholder="AB12CD34" />
                </div>

                <p v-if="Object.values(form.errors).length" class="text-xs text-rose-400">{{ Object.values(form.errors)[0] }}</p>

                <button :disabled="form.processing" class="w-full rounded-xl bg-gradient-to-r from-brand-500 to-cyan-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
                    {{ form.processing ? 'Joining...' : 'Enter Live Room' }}
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

