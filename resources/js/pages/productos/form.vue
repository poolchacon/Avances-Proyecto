<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import productos from '@/routes/productos/index.js';
import Button from '@/components/ui/button/Button.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import InputError from '@/components/InputError.vue';
import { Card } from '@/components/ui/card';
import { Save, Upload } from 'lucide-vue-next';
import { Producto, Categoria } from '@/types/index.js';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Productos', href: productos.index() },
        ],
    },
});

const props = defineProps<{
    producto: Producto;
    categorias: Categoria[];
}>();

const handleFileChange = (e: any) => {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onloadend = () => {
        props.producto.imagen = reader.result as string;
    };
    reader.readAsDataURL(file);
};

const action = props.producto.id
    ? productos.update.form({ producto: props.producto.id })
    : productos.store.form();
</script>

<template>
    <Head title="Productos" />

    <div class="flex h-full flex-1 flex-col items-center gap-4 rounded-xl p-4">
        <Form v-bind="action" v-slot="{ errors, processing }">
            <div class="-mt-16.5 mb-7.5 flex justify-between gap-4 md:w-200">
                <h1 class="text-2xl">{{ producto.id ? 'Editar Producto' : 'Nuevo Producto' }}</h1>
                <Button :disabled="processing">
                    <Save /> {{ processing ? 'Guardando...' : 'Guardar' }}
                </Button>
            </div>

            <div class="grid gap-4 md:w-200">
                <div class="flex gap-4">
                    <div class="w-8/12">
                        <Card class="p-6">
                            <div class="grid w-full gap-2 mb-4">
                                <Label for="nombre">Nombre *</Label>
                                <Input id="nombre" name="nombre" :defaultValue="producto.nombre" placeholder="Nombre del producto" />
                                <InputError :message="errors.nombre" class="mt-2" />
                            </div>
                            <div class="grid w-full gap-2 mb-4">
                                <Label for="descripcion">Descripción</Label>
                                <Textarea id="descripcion" name="descripcion" :defaultValue="producto.descripcion" placeholder="Descripción del producto" />
                                <InputError :message="errors.descripcion" class="mt-2" />
                            </div>
                            <div class="grid w-full gap-2 mb-4">
                                <Label for="precio">Precio (S/.) *</Label>
                                <Input id="precio" name="precio" type="number" step="0.01" min="0" :defaultValue="producto.precio" placeholder="0.00" />
                                <InputError :message="errors.precio" class="mt-2" />
                            </div>
                            <div class="grid w-full gap-2 mb-4">
                                <Label for="categoria_id">Categoría *</Label>
                                <select id="categoria_id" name="categoria_id"
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                    <option value="" disabled>Seleccione una categoría</option>
                                    <option v-for="cat in categorias" :key="cat.id" :value="cat.id"
                                        :selected="producto.categoria_id == cat.id">
                                        {{ cat.nombre }}
                                    </option>
                                </select>
                                <InputError :message="errors.categoria_id" class="mt-2" />
                            </div>
                            <div class="grid w-full gap-2 mb-4">
                                <Label for="estado">Estado</Label>
                                <select id="estado" name="estado"
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                    <option value="A" :selected="producto.estado === 'A'">Activo</option>
                                    <option value="I" :selected="producto.estado === 'I'">Inactivo</option>
                                </select>
                                <InputError :message="errors.estado" class="mt-2" />
                            </div>
                            <div class="mt-6 flex justify-between">
                                <Button variant="secondary" as-child>
                                    <Link :href="productos.index()">Cancelar</Link>
                                </Button>
                                <Button :disabled="processing">
                                    <Save /> {{ processing ? 'Guardando...' : 'Guardar' }}
                                </Button>
                            </div>
                        </Card>
                    </div>

                    <div class="w-4/12">
                        <Card class="p-4">
                            <Label class="mb-2 block">Imagen</Label>
                            <div class="group relative grid place-items-center overflow-hidden rounded-md bg-muted transition-all hover:bg-primary/10 aspect-square">
                                <input id="imagen" type="file" name="imagen"
                                    @change="handleFileChange"
                                    class="absolute inset-0 z-10 cursor-pointer opacity-0" />
                                <Upload class="absolute text-muted-foreground opacity-50" />
                                <img :src="producto.imagen ? producto.imagen : '/apple-touch-icon.png'"
                                    class="h-40 w-40 object-cover" />
                            </div>
                        </Card>
                    </div>
                </div>
            </div>
        </Form>
    </div>
</template>
