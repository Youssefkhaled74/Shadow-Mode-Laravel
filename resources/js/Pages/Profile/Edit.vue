<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout title="Profile & Settings">
        <div class="mx-auto max-w-5xl space-y-6">
                <div class="glass p-4 sm:p-8">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="glass p-4 sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="glass p-4 sm:p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
        </div>
    </AuthenticatedLayout>
</template>

