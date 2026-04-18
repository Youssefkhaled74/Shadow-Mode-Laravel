<script setup>
import { computed } from 'vue';

const props = defineProps({
    points: {
        type: Array,
        default: () => [],
    },
});

const normalized = computed(() => {
    if (!props.points.length) {
        return [];
    }

    const max = Math.max(...props.points.map((point) => Number(point.score)));

    return props.points.map((point, index) => {
        const x = (index / Math.max(props.points.length - 1, 1)) * 100;
        const y = 100 - (Number(point.score) / Math.max(max, 1)) * 100;

        return `${x},${y}`;
    });
});

const polyline = computed(() => normalized.value.join(' '));
</script>

<template>
    <div class="glass p-5">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-muted">Performance Trend</h3>
            <span class="text-xs text-muted">Last 14 Days</span>
        </div>

        <div v-if="points.length" class="relative h-40">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="h-full w-full">
                <polyline :points="polyline" fill="none" stroke="url(#trendGradient)" stroke-width="2.5" />
                <defs>
                    <linearGradient id="trendGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#14b8a6" />
                        <stop offset="100%" stop-color="#0ea5e9" />
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div v-else class="rounded-xl border border-dashed border-shell/70 p-5 text-center text-sm text-muted">
            Trend data will appear after your first completed session.
        </div>
    </div>
</template>

