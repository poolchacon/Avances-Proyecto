<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import 'vue-sonner/style.css';
import { toast, Toaster } from "vue-sonner";

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();

const page = usePage();

watch(
    () => page.props.flash,
    (flash) => {
        if (flash.message) {
            // @ts-ignore
            toast.success(flash.message);
        }

        if (flash.error) {
            // @ts-ignore
            toast.error(flash.error);
        }
    },
)

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toaster position="top-center" richColors />
        <slot />
    </AppLayout>
</template>
