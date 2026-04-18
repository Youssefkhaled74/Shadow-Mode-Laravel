<script setup>
defineProps({
    title: String,
    value: [String, Number],
    subtitle: {
        type: String,
        default: '',
    },
    accent: {
        type: String,
        default: 'from-brand-500 to-cyan-500',
    },
});
</script>

<template>
    <div class="glass p-5 transition hover:-translate-y-0.5 hover:border-white/20">
        <div class="mb-4 flex items-center justify-between">
            <p class="text-xs uppercase tracking-[0.2em] text-muted">{{ title }}</p>
            <span :class="accent" class="h-2.5 w-10 rounded-full bg-gradient-to-r"></span>
        </div>
        <p class="text-3xl font-semibold tracking-tight text-shell">{{ value }}</p>
        <p v-if="subtitle" class="mt-2 text-xs text-muted">{{ subtitle }}</p>
    </div>
</template>

