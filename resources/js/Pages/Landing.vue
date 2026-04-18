<script setup>
import { Head, Link } from '@inertiajs/vue3';

const features = [
    {
        title: 'Real-time Coaching Loop',
        copy: 'Live hints, score pulses, and warnings arrive inside the room while the conversation is happening.',
    },
    {
        title: 'Presence + Activity Stream',
        copy: 'Track who is in the room, what changed, and when momentum shifted in one continuous feed.',
    },
    {
        title: 'Post-Session Intelligence',
        copy: 'Generate a performance report with timeline highlights, best/weakest responses, and focused next steps.',
    },
];
</script>

<template>
    <Head title="Shadow Mode - Live Coaching" />

    <div class="relative min-h-screen overflow-hidden bg-shell text-shell">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute left-[8%] top-[12%] h-80 w-80 rounded-full bg-brand-500/30 blur-3xl"></div>
            <div class="absolute right-[8%] top-[20%] h-72 w-72 rounded-full bg-cyan-500/30 blur-3xl"></div>
            <div class="grid-overlay absolute inset-0 opacity-40"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-8 sm:px-6 lg:px-8">
            <header class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="/images/shadow-mode-logo.png" alt="Shadow Mode" class="h-16 w-auto rounded-md object-contain" />
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('login')" class="rounded-lg border border-shell/80 px-4 py-2 text-sm text-muted hover:text-shell">Login</Link>
                    <Link :href="route('register')" class="rounded-lg bg-gradient-to-r from-brand-500 to-cyan-500 px-4 py-2 text-sm font-semibold text-white">Start Free</Link>
                </div>
            </header>

            <main class="grid flex-1 items-center gap-12 py-14 lg:grid-cols-[1.1fr_0.9fr]">
                <section>
                    <p class="mb-4 inline-flex rounded-full border border-shell/70 bg-panel/60 px-4 py-1 text-xs uppercase tracking-[0.2em] text-brand-300">
                        Built for interview, sales, and negotiation rehearsal
                    </p>
                    <h1 class="max-w-3xl text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                        Enter the room. Perform live. Get coached in real time.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg text-muted">
                        Shadow Mode blends live simulation, instant feedback, and post-session analytics so your communication skills compound every session.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link :href="route('register')" class="rounded-xl bg-gradient-to-r from-brand-500 to-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-brand-900/30">
                            Launch Workspace
                        </Link>
                        <Link :href="route('sessions.join')" class="rounded-xl border border-shell/70 px-6 py-3 text-sm text-muted hover:text-shell">
                            Join by Code
                        </Link>
                    </div>
                </section>

                <section class="glass p-6 sm:p-7">
                    <h2 class="text-sm uppercase tracking-[0.2em] text-muted">Why teams choose Shadow Mode</h2>
                    <div class="mt-5 space-y-4">
                        <article v-for="feature in features" :key="feature.title" class="rounded-xl border border-shell/70 bg-shell/20 p-4">
                            <h3 class="text-base font-semibold text-shell">{{ feature.title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-muted">{{ feature.copy }}</p>
                        </article>
                    </div>
                </section>
            </main>
        </div>
    </div>
</template>

