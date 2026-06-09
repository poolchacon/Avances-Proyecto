<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import stock from '@/routes/stock/index.js';
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
            { title: 'Stock', href: stock.index() },
            { title: 'Registrar Movimiento' },
        ],
    },
});

const props = defineProps<{
    productos: any;
    sedes: any;
}>();

const action = stock.store.form();
</script>

<template>
    <Head title="Registrar Movimiento de Stock" />

    <div class="flex h-full flex-1 flex-col items-center gap-4 rounded-xl p-4">
        <Form v-bind="action" v-slot="{ errors, processing }">
            <div class="-mt-16.5 mb-7.5 flex justify-between gap-4 md:w-200">
                <h1 class="text-2xl">Registrar Movimiento de Stock</h1>
                <Button :disabled="processing">
                    <Save /> {{ processing ? 'Guardando...' : 'Guardar' }}
                </Button>
            </div>

            <div class="grid gap-4 md:w-200">
                <Card class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid w-full gap-2">
                            <Label for="producto_id">Producto *</Label>
                            <select id="producto_id" name="producto_id"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="">— Seleccione producto —</option>
                                <option v-for="p in productos.data ?? productos" :key="p.id" :value="p.id">
                                    {{ p.nombre }}
                                </option>
                            </select>
                            <InputError :message="errors.producto_id" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="sede_id">Sede *</Label>
                            <select id="sede_id" name="sede_id"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="">— Seleccione sede —</option>
                                <option v-for="s in sedes.data ?? sedes" :key="s.id" :value="s.id">
                                    {{ s.nombre }}
                                </option>
                            </select>
                            <InputError :message="errors.sede_id" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="tipo">Tipo de movimiento *</Label>
                            <select id="tipo" name="tipo"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="ENTRADA">ENTRADA (agregar stock)</option>
                                <option value="SALIDA">SALIDA (reducir stock)</option>
                                <option value="AJUSTE">AJUSTE (establecer cantidad exacta)</option>
                            </select>
                            <InputError :message="errors.tipo" />
                        </div>
                        <div class="grid w-full gap-2">
                            <Label for="cantidad">Cantidad *</Label>
                            <Input id="cantidad" name="cantidad" type="number" step="1" min="0" defaultValue="0" />
                            <InputError :message="errors.cantidad" />
                        </div>
                        <div class="grid w-full gap-2 md:col-span-2">
                            <Label for="motivo">Motivo / Observación</Label>
                            <Input id="motivo" name="motivo" placeholder="Ej: Compra de proveedor, merma, inventario..." />
                            <InputError :message="errors.motivo" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <Button variant="secondary" as-child>
                            <Link :href="stock.index()">Cancelar</Link>
                        </Button>
                        <Button :disabled="processing">
                            <Save /> {{ processing ? 'Guardando...' : 'Guardar Movimiento' }}
                        </Button>
                    </div>
                </Card>
            </div>
        </Form>
    </div>
</template>
