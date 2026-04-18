<script setup>
defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="glass p-5">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-[0.18em] text-muted">Live Activity</h3>
        <div v-if="items.length" class="space-y-3">
            <div v-for="item in items" :key="item.id" class="rounded-xl border border-shell/70 bg-shell/20 p-3">
                <p class="text-sm font-semibold text-shell">{{ item.title }}</p>
                <p class="mt-1 text-xs text-muted">{{ item.message || item.session_title }}</p>
                <p class="mt-2 text-[11px] uppercase tracking-[0.14em] text-muted">{{ item.occurred_at ?? item.created_at }}</p>
            </div>
        </div>
        <div v-else class="rounded-xl border border-dashed border-shell/70 p-6 text-center text-sm text-muted">
            No activity yet. Start a live session to populate this feed.
        </div>
    </div>
</template>

