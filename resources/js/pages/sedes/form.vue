<script setup lang="ts">
import { Head, Link, Form, usePage } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import sedes from '@/routes/sedes/index.js';
import Button from '@/components/ui/button/Button.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import InputError from '@/components/InputError.vue';
import { Card } from '@/components/ui/card';
import { Save } from 'lucide-vue-next';
import { Sede } from '@/types/index.js';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Sedes', href: sedes.index() },
        ],
    },
});

const props = defineProps<{ sede: Sede }>();
const { url } = usePage();

const action = props.sede.id
    ? sedes.update.form({ sede: props.sede.id })
    : sedes.store.form();
</script>

<template>
    <Head title="Sedes" />

    <div class="flex h-full flex-1 flex-col items-center gap-4 rounded-xl p-4">
        <Form v-bind="action" v-slot="{ errors, processing }">
            <div class="-mt-16.5 mb-7.5 flex justify-between gap-4 md:w-200">
                <h1 class="text-2xl">{{ sede.id ? 'Editar Sede' : 'Nueva Sede' }}</h1>
                <Button :disabled="processing">
                    <Save /> {{ processing ? 'Guardando...' : 'Guardar' }}
                </Button>
            </div>

            <div class="grid gap-4 md:w-200">
                <Card class="p-6">
                    <div class="grid w-full gap-2 mb-4">
                        <Label for="nombre">Nombre *</Label>
                        <Input id="nombre" name="nombre" :defaultValue="sede.nombre" placeholder="Nombre de la sede" />
                        <InputError :message="errors.nombre" class="mt-2" />
                    </div>
                    <div class="grid w-full gap-2 mb-4">
                        <Label for="direccion">Dirección</Label>
                        <Input id="direccion" name="direccion" :defaultValue="sede.direccion" placeholder="Dirección" />
                        <InputError :message="errors.direccion" class="mt-2" />
                    </div>
                    <div class="grid w-full gap-2 mb-4">
                        <Label for="telefono">Teléfono</Label>
                        <Input id="telefono" name="telefono" :defaultValue="sede.telefono" placeholder="Teléfono" />
                        <InputError :message="errors.telefono" class="mt-2" />
                    </div>
                    <div class="grid w-full gap-2 mb-4">
                        <Label for="estado">Estado</Label>
                        <select id="estado" name="estado"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                            <option value="A" :selected="sede.estado === 'A'">Activo</option>
                            <option value="I" :selected="sede.estado === 'I'">Inactivo</option>
                        </select>
                        <InputError :message="errors.estado" class="mt-2" />
                    </div>
                    <div class="mt-6 flex justify-between">
                        <Button variant="secondary" as-child>
                            <Link :href="sedes.index()">Cancelar</Link>
                        </Button>
                        <Button :disabled="processing">
                            <Save /> {{ processing ? 'Guardando...' : 'Guardar' }}
                        </Button>
                    </div>
                </Card>
            </div>
        </Form>
    </div>
</template>
