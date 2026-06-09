<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import usuarios from '@/routes/usuarios/index.js';
import Button from '@/components/ui/button/Button.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import InputError from '@/components/InputError.vue';
import { Card } from '@/components/ui/card';
import { Save } from 'lucide-vue-next';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Usuarios', href: usuarios.index() },
        ],
    },
});

const props = defineProps<{
    usuario: any;
    sedes: any[];
}>();

const action = props.usuario.id
    ? usuarios.update.form({ usuario: props.usuario.id })
    : usuarios.store.form();
</script>

<template>
    <Head title="Usuarios" />

    <div class="flex h-full flex-1 flex-col items-center gap-4 rounded-xl p-4">
        <Form v-bind="action" v-slot="{ errors, processing }">
            <div class="-mt-16.5 mb-7.5 flex justify-between gap-4 md:w-200">
                <h1 class="text-2xl">{{ usuario.id ? 'Editar Usuario' : 'Nuevo Usuario' }}</h1>
                <Button :disabled="processing">
                    <Save /> {{ processing ? 'Guardando...' : 'Guardar' }}
                </Button>
            </div>

            <div class="grid gap-4 md:w-200">
                <Card class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid w-full gap-2">
                            <Label for="name">Nombre completo *</Label>
                            <Input id="name" name="name" :defaultValue="usuario.name" placeholder="Nombre completo" />
                            <InputError :message="errors.name" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="email">Correo electrónico *</Label>
                            <Input id="email" name="email" type="email" :defaultValue="usuario.email" placeholder="correo@deleite.com" />
                            <InputError :message="errors.email" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="password">
                                {{ usuario.id ? 'Nueva contraseña (dejar vacío para no cambiar)' : 'Contraseña *' }}
                            </Label>
                            <Input id="password" name="password" type="password" placeholder="Mínimo 8 caracteres" />
                            <InputError :message="errors.password" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="sede_id">Sede *</Label>
                            <select id="sede_id" name="sede_id"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="">— Seleccione sede —</option>
                                <option v-for="sede in sedes.data ?? sedes" :key="sede.id"
                                    :value="sede.id"
                                    :selected="usuario.sede_id === sede.id">
                                    {{ sede.nombre }}
                                </option>
                            </select>
                            <InputError :message="errors.sede_id" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="rol">Rol *</Label>
                            <select id="rol" name="rol"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="ADMINISTRADOR" :selected="usuario.rol === 'ADMINISTRADOR'">Administrador</option>
                                <option value="VENDEDOR" :selected="usuario.rol === 'VENDEDOR'">Vendedor</option>
                            </select>
                            <InputError :message="errors.rol" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="estado">Estado *</Label>
                            <select id="estado" name="estado"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="A" :selected="usuario.estado === 'A'">Activo</option>
                                <option value="I" :selected="usuario.estado === 'I'">Inactivo</option>
                            </select>
                            <InputError :message="errors.estado" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <Button variant="secondary" as-child>
                            <Link :href="usuarios.index()">Cancelar</Link>
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
