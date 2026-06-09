<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import ventas from '@/routes/ventas/index.js';
import Button from '@/components/ui/button/Button.vue';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import {
    Table, TableBody, TableCell, TableHead,
    TableHeader, TableRow,
} from '@/components/ui/table';
import { ArrowLeft, Printer } from 'lucide-vue-next';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Ventas', href: ventas.index() },
            { title: 'Detalle de Venta' },
        ],
    },
});

defineProps<{ venta: any }>();

const tipoPedidoLabel: Record<string, string> = {
    SALON: 'Salón', DELIVERY: 'Delivery', LLEVAR: 'Para llevar',
};
const metodoPagoLabel: Record<string, string> = {
    EFECTIVO: 'Efectivo', YAPE: 'Yape', PLIN: 'Plin', TARJETA: 'Tarjeta', MIXTO: 'Mixto',
};
</script>

<template>
    <Head title="Detalle de Venta" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4 max-w-3xl mx-auto">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <Button variant="secondary" as-child size="sm">
                    <Link :href="ventas.index()"><ArrowLeft class="mr-1 h-4 w-4" /> Volver</Link>
                </Button>
                <h1 class="text-xl font-semibold">Venta #{{ venta.id }}</h1>
                <Badge :variant="venta.estado === 'COMPLETADA' ? 'default' : 'destructive'">
                    {{ venta.estado }}
                </Badge>
            </div>
            <Button variant="outline" size="sm" @click="window.print()">
                <Printer class="mr-1 h-4 w-4" /> Imprimir
            </Button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <Card class="p-4">
                <h2 class="font-semibold mb-3">Información General</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Fecha:</span>
                        <span>{{ venta.fecha_venta }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Sede:</span>
                        <span>{{ venta.sede }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Cliente:</span>
                        <span>{{ venta.cliente ?? 'Sin cliente' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Atendió:</span>
                        <span>{{ venta.usuario }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Tipo pedido:</span>
                        <span>{{ tipoPedidoLabel[venta.tipo_pedido] ?? venta.tipo_pedido }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Método pago:</span>
                        <span>{{ metodoPagoLabel[venta.metodo_pago] ?? venta.metodo_pago }}</span>
                    </div>
                </div>
            </Card>
            <Card class="p-4">
                <h2 class="font-semibold mb-3">Totales</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Subtotal:</span>
                        <span>S/ {{ Number(venta.subtotal).toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Descuento:</span>
                        <span>S/ {{ Number(venta.descuento).toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-base pt-2 border-t">
                        <span>TOTAL:</span>
                        <span>S/ {{ Number(venta.total).toFixed(2) }}</span>
                    </div>
                </div>
                <div v-if="venta.observaciones" class="mt-3 pt-3 border-t">
                    <p class="text-xs text-muted-foreground">Observaciones:</p>
                    <p class="text-sm">{{ venta.observaciones }}</p>
                </div>
            </Card>
        </div>

        <Card class="p-4">
            <h2 class="font-semibold mb-3">Detalle de Productos</h2>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Producto</TableHead>
                        <TableHead class="text-center">Cantidad</TableHead>
                        <TableHead class="text-end">Precio Unit.</TableHead>
                        <TableHead class="text-end">Subtotal</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="detalle in venta.detalles" :key="detalle.id">
                        <TableCell>{{ detalle.producto }}</TableCell>
                        <TableCell class="text-center">{{ detalle.cantidad }}</TableCell>
                        <TableCell class="text-end">S/ {{ Number(detalle.precio_unitario).toFixed(2) }}</TableCell>
                        <TableCell class="text-end font-semibold">S/ {{ Number(detalle.subtotal).toFixed(2) }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </Card>
    </div>
</template>
