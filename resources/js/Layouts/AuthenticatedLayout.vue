<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const ziggyRoute = (...args) => window.route(...args);
const openMobileNav = ref(false);
const theme = ref('dark');

const user = computed(() => page.props.auth?.user ?? null);
const isAdmin = computed(() => user.value?.roles?.includes('admin'));

const navItems = computed(() => {
    const base = [
        { label: 'Dashboard', href: ziggyRoute('dashboard'), active: ziggyRoute().current('dashboard') },
        { label: 'Sessions', href: ziggyRoute('sessions.index'), active: ziggyRoute().current('sessions.*') || ziggyRoute().current('rooms.*') },
        { label: 'Create Session', href: ziggyRoute('sessions.create'), active: ziggyRoute().current('sessions.create') },
        { label: 'Join Session', href: ziggyRoute('sessions.join'), active: ziggyRoute().current('sessions.join') },
        { label: 'Settings', href: ziggyRoute('profile.edit'), active: ziggyRoute().current('profile.*') },
    ];

    if (isAdmin.value) {
        base.push({ label: 'Admin', href: ziggyRoute('admin.dashboard'), active: ziggyRoute().current('admin.*') });
    }

    return base;
});

const flash = computed(() => page.props.flash ?? {});

function applyTheme(nextTheme) {
    theme.value = nextTheme;
    const root = document.documentElement;

    if (nextTheme === 'dark') {
        root.classList.add('dark');
    } else {
        root.classList.remove('dark');
    }

    localStorage.setItem('shadow-theme', nextTheme);
}

function toggleTheme() {
    applyTheme(theme.value === 'dark' ? 'light' : 'dark');
}

onMounted(() => {
    const saved = localStorage.getItem('shadow-theme');
    applyTheme(saved ?? 'dark');
});
</script>

<template>
    <div class="min-h-screen bg-shell text-shell transition-colors duration-300">
        <Head :title="title" />

        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute left-[-120px] top-[-120px] h-72 w-72 rounded-full bg-brand-500/30 blur-3xl"></div>
            <div class="absolute bottom-[-100px] right-[-80px] h-80 w-80 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="grid-overlay absolute inset-0 opacity-40"></div>
        </div>

        <div class="relative flex min-h-screen">
            <aside class="hidden w-72 shrink-0 border-r border-shell/60 bg-panel/80 p-6 backdrop-blur-xl lg:block">
                <Link :href="route('dashboard')" class="mb-8 flex items-center gap-3">
                    <img src="/images/shadow-mode-logo.png" alt="Shadow Mode" class="h-14 w-auto rounded-md object-contain" />
                </Link>

                <nav class="space-y-2">
                    <Link
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        class="group flex items-center justify-between rounded-xl px-4 py-3 text-sm transition"
                        :class="item.active ? 'bg-brand-500/20 text-brand-100 ring-1 ring-brand-400/50' : 'text-muted hover:bg-white/5 hover:text-shell'"
                    >
                        <span>{{ item.label }}</span>
                        <span class="h-2 w-2 rounded-full" :class="item.active ? 'bg-brand-300' : 'bg-transparent group-hover:bg-brand-400/50'"></span>
                    </Link>
                </nav>
            </aside>

            <div class="flex min-h-screen flex-1 flex-col">
                <header class="sticky top-0 z-30 border-b border-shell/60 bg-panel/70 px-4 py-3 backdrop-blur-xl sm:px-6">
                    <div class="flex items-center justify-between gap-4">
                        <button class="rounded-lg border border-shell/80 px-3 py-2 text-xs text-muted lg:hidden" @click="openMobileNav = !openMobileNav">
                            Menu
                        </button>

                        <div>
                            <h1 class="text-lg font-semibold tracking-tight text-shell">{{ title }}</h1>
                            <p class="text-xs text-muted">Precision coaching in real time</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button class="rounded-lg border border-shell/80 px-3 py-2 text-xs text-muted hover:text-shell" @click="toggleTheme">
                                {{ theme === 'dark' ? 'Light' : 'Dark' }}
                            </button>
                            <Link :href="route('logout')" method="post" as="button" class="rounded-lg border border-shell/80 px-3 py-2 text-xs text-muted hover:text-shell">
                                Logout
                            </Link>
                        </div>
                    </div>
                </header>

                <div v-if="flash.success || flash.warning || flash.error" class="mx-4 mt-4 rounded-xl border border-shell/70 bg-panel/80 p-3 text-sm sm:mx-6">
                    <p v-if="flash.success" class="text-emerald-400">{{ flash.success }}</p>
                    <p v-if="flash.warning" class="text-amber-300">{{ flash.warning }}</p>
                    <p v-if="flash.error" class="text-rose-400">{{ flash.error }}</p>
                </div>

                <main class="flex-1 p-4 sm:p-6">
                    <slot />
                </main>
            </div>
        </div>

        <transition enter-active-class="transition duration-200" enter-from-class="opacity-0" leave-active-class="transition duration-150" leave-to-class="opacity-0">
            <div v-if="openMobileNav" class="fixed inset-0 z-40 bg-black/60 lg:hidden" @click="openMobileNav = false">
                <div class="h-full w-72 border-r border-shell/60 bg-panel p-6" @click.stop>
                    <p class="mb-4 text-xs uppercase tracking-[0.2em] text-brand-300">Navigation</p>
                    <div class="space-y-2">
                        <Link
                            v-for="item in navItems"
                            :key="item.label"
                            :href="item.href"
                            class="block rounded-xl px-4 py-3 text-sm"
                            :class="item.active ? 'bg-brand-500/20 text-brand-100' : 'text-muted hover:bg-white/5 hover:text-shell'"
                        >
                            {{ item.label }}
                        </Link>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Shadow Mode',
        },
    },
};
</script>

