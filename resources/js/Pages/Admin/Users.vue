<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    users: Object,
});
const ziggyRoute = (...args) => window.route(...args);

const form = useForm({
    name: '',
    email: '',
    role: 'user',
    headline: '',
    bio: '',
    timezone: 'UTC',
});

function editUser(user) {
    form.name = user.name;
    form.email = user.email;
    form.role = user.roles?.[0]?.name ?? 'user';
    form.headline = user.headline ?? '';
    form.bio = user.bio ?? '';
    form.timezone = user.timezone ?? 'UTC';

    form.patch(ziggyRoute('admin.users.update', user.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Admin Users" />

    <AuthenticatedLayout title="User Management">
        <div class="space-y-4">
            <div class="glass overflow-hidden">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-shell/70 bg-shell/20 text-xs uppercase tracking-[0.16em] text-muted">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id" class="border-b border-shell/60 last:border-b-0">
                            <td class="px-4 py-3 text-shell">{{ user.name }}</td>
                            <td class="px-4 py-3 text-muted">{{ user.email }}</td>
                            <td class="px-4 py-3 text-muted">{{ user.roles?.[0]?.name ?? 'user' }}</td>
                            <td class="px-4 py-3">
                                <button class="text-xs text-brand-300" @click="editUser(user)">Promote/Update</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

