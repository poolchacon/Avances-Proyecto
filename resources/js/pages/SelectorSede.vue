<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { Store } from 'lucide-vue-next';

defineProps<{
    sedes: Array<{ id: number; nombre: string; direccion: string; telefono: string | null }>;
}>();

const page = usePage();
const user = page.props.auth.user as any;

const seleccionarSede = (sedeId: number) => {
    router.post('/selector-sede', { sede_id: sedeId });
};
</script>

<template>
    <Head title="Seleccionar Sede" />

    <div class="flex flex-col items-center justify-center min-h-full p-8 gap-8">

        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-bold text-foreground">Selecciona tu sede</h1>
            <p class="text-muted-foreground text-sm">Elige la sede desde donde trabajarás hoy</p>
            <div class="inline-flex items-center gap-2 bg-muted text-muted-foreground rounded-full px-4 py-1.5 text-sm mt-2">
                <span class="font-medium text-foreground">{{ user?.name }}</span>
                <span>·</span>
                <span>{{ user?.rol }}</span>
            </div>
        </div>

        <!-- Cards de sedes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl w-full">
            <button
                v-for="sede in sedes"
                :key="sede.id"
                type="button"
                @click="seleccionarSede(sede.id)"
                class="group flex flex-col items-center gap-3 p-6 rounded-xl border border-border bg-card hover:bg-accent hover:border-primary transition-all duration-150 cursor-pointer text-center"
            >
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                    <Store class="h-6 w-6" />
                </div>
                <div>
                    <h2 class="font-semibold text-foreground text-base">{{ sede.nombre }}</h2>
                    <p v-if="sede.direccion" class="text-xs text-muted-foreground mt-0.5">{{ sede.direccion }}</p>
                    <p v-if="sede.telefono" class="text-xs text-muted-foreground">{{ sede.telefono }}</p>
                </div>
                <span class="text-xs font-medium text-primary group-hover:underline">Seleccionar →</span>
            </button>
        </div>
    </div>
</template>
