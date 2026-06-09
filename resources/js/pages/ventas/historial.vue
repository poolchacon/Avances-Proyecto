<script setup lang="ts">
import { Head, Link, router, Form } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { Badge } from '@/components/ui/badge';
import { Eye, Ban, Search, ArrowLeft } from 'lucide-vue-next';
import {
    Table, TableBody, TableCell, TableHead,
    TableHeader, TableRow,
} from '@/components/ui/table';
import ventas from '@/routes/ventas/index.js';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Ventas', href: '/ventas' },
            { title: 'Historial' },
        ],
    },
});

defineProps<{
    collection: any;
    search: string | null;
    fecha: string | null;
}>();

const onAnular = (id: number) => {
    if (window.confirm('¿Está seguro de anular esta venta?')) {
        router.delete(ventas.destroy({ venta: id }));
    }
};

const tipoPedidoLabel: Record<string, string> = { SALON: 'Salón', DELIVERY: 'Delivery', LLEVAR: 'Llevar' };
const metodoPagoLabel: Record<string, string> = { EFECTIVO: 'Efectivo', YAPE: 'Yape', PLIN: 'Plin', TARJETA: 'Tarjeta', MIXTO: 'Mixto' };
</script>

<template>
    <Head title="Historial de Ventas" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="absolute top-1.5 right-4 flex items-center gap-2">
            <Button variant="secondary" as-child>
                <Link href="/ventas"><ArrowLeft class="h-4 w-4 mr-1" /> Ir al POS</Link>
            </Button>
        </div>

        <div class="mb-0">
            <Form method="GET" :href="'/ventas/historial'" class="flex gap-2 flex-wrap">
                <Input name="search" :default-value="search ?? ''" placeholder="Buscar cliente..." class="max-w-xs" />
                <Input name="fecha" type="date" :default-value="fecha ?? ''" class="max-w-xs" />
                <Button variant="secondary"><Search class="mr-2 h-4 w-4" /> Buscar</Button>
            </Form>
        </div>

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>#</TableHead>
                    <TableHead>Fecha</TableHead>
                    <TableHead>Cliente</TableHead>
                    <TableHead>Sede</TableHead>
                    <TableHead>Tipo</TableHead>
                    <TableHead>Método Pago</TableHead>
                    <TableHead class="text-end">Total</TableHead>
                    <TableHead class="text-center">Estado</TableHead>
                    <TableHead class="text-end">Acciones</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="item in collection.data" :key="item.id">
                    <TableCell class="font-mono text-xs">#{{ item.id }}</TableCell>
                    <TableCell>{{ item.fecha_venta }}</TableCell>
                    <TableCell>{{ item.cliente ?? 'Sin cliente' }}</TableCell>
                    <TableCell>{{ item.sede }}</TableCell>
                    <TableCell>{{ tipoPedidoLabel[item.tipo_pedido] ?? item.tipo_pedido }}</TableCell>
                    <TableCell>{{ metodoPagoLabel[item.metodo_pago] ?? item.metodo_pago }}</TableCell>
                    <TableCell class="text-end font-semibold">S/ {{ Number(item.total).toFixed(2) }}</TableCell>
                    <TableCell class="text-center">
                        <Badge :variant="item.estado === 'COMPLETADA' ? 'default' : 'destructive'">
                            {{ item.estado }}
                        </Badge>
                    </TableCell>
                    <TableCell class="text-end">
                        <Button variant="secondary" size="sm" class="mr-2" title="Ver detalle" as-child>
                            <Link :href="ventas.show({ venta: item.id })"><Eye /></Link>
                        </Button>
                        <Button v-if="item.estado === 'COMPLETADA'" variant="destructive" size="sm" title="Anular" @click="onAnular(item.id)">
                            <Ban />
                        </Button>
                    </TableCell>
                </TableRow>
                <TableRow v-if="!collection.data.length">
                    <TableCell colspan="9" class="text-center text-muted-foreground py-8">No hay ventas registradas.</TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <div class="mt-1 flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
                Página {{ collection.meta.current_page }} de {{ collection.meta.last_page }}
                ({{ collection.meta.total }} registros)
            </div>
            <div class="flex gap-1" v-if="collection.meta.links.length > 3">
                <Button v-for="(link, i) in collection.meta.links" :key="i"
                    variant="ghost" size="sm" :disabled="!link.url" as-child>
                    <Link :href="link.url || '#'" :class="{ 'bg-accent': link.active }">
                        <span v-html="link.label"></span>
                    </Link>
                </Button>
            </div>
        </div>
    </div>
</template>
