<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { Card } from '@/components/ui/card';
import {
    Table, TableBody, TableCell, TableHead,
    TableHeader, TableRow,
} from '@/components/ui/table';
import { TrendingUp, ShoppingBag, CreditCard, MapPin } from 'lucide-vue-next';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Reportes' },
        ],
    },
});

const props = defineProps<{
    desde: string;
    hasta: string;
    sede_id: number | null;
    es_admin: boolean;
    sedes: any[];
    totales: { total_ventas: number; total_ingresos: number } | null;
    por_metodo: any[];
    por_sede: any[];
    top_productos: any[];
    ventas_dia: any[];
}>();

const metodoPagoLabel: Record<string, string> = {
    EFECTIVO: 'Efectivo', YAPE: 'Yape', PLIN: 'Plin', TARJETA: 'Tarjeta', MIXTO: 'Mixto',
};
</script>

<template>
    <Head title="Reportes" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4 overflow-y-auto">

        <!-- Filtros -->
        <Form method="GET" href="/reportes" class="flex gap-2 flex-wrap items-end">
            <div class="grid gap-1">
                <label class="text-xs text-muted-foreground">Desde</label>
                <Input name="desde" type="date" :default-value="desde" class="h-8 text-sm" />
            </div>
            <div class="grid gap-1">
                <label class="text-xs text-muted-foreground">Hasta</label>
                <Input name="hasta" type="date" :default-value="hasta" class="h-8 text-sm" />
            </div>
            <!-- Filtro de sede: SOLO ADMIN -->
            <div v-if="es_admin" class="grid gap-1">
                <label class="text-xs text-muted-foreground">Sede</label>
                <select name="sede_id"
                    class="flex h-8 rounded-md border border-input bg-transparent px-2 text-sm shadow-sm">
                    <option value="">Todas las sedes</option>
                    <option v-for="s in sedes" :key="s.id" :value="s.id" :selected="sede_id === s.id">
                        {{ s.nombre }}
                    </option>
                </select>
            </div>
            <Button type="submit" variant="secondary" class="h-8">
                <TrendingUp class="h-4 w-4 mr-1" /> Generar
            </Button>
        </Form>

        <!-- Tarjetas resumen -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <Card class="p-4 text-center">
                <div class="text-3xl font-bold">{{ totales?.total_ventas ?? 0 }}</div>
                <div class="text-sm text-muted-foreground mt-1">Ventas completadas</div>
            </Card>
            <Card class="p-4 text-center">
                <div class="text-3xl font-bold text-green-500">
                    S/ {{ Number(totales?.total_ingresos ?? 0).toFixed(2) }}
                </div>
                <div class="text-sm text-muted-foreground mt-1">Ingresos totales</div>
            </Card>
            <Card class="p-4 text-center">
                <div class="text-3xl font-bold text-blue-400">
                    S/ {{ totales?.total_ventas ? (Number(totales.total_ingresos) / totales.total_ventas).toFixed(2) : '0.00' }}
                </div>
                <div class="text-sm text-muted-foreground mt-1">Ticket promedio</div>
            </Card>
            <Card class="p-4 text-center">
                <div class="text-xl font-bold text-primary truncate">
                    {{ top_productos.length > 0 ? top_productos[0]?.producto?.nombre ?? '—' : '—' }}
                </div>
                <div class="text-sm text-muted-foreground mt-1">Producto más vendido</div>
            </Card>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Top productos -->
            <Card class="p-4">
                <div class="flex items-center gap-2 mb-3 font-semibold">
                    <ShoppingBag class="h-4 w-4 text-primary" />
                    Top 10 Productos
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>
                            <TableHead>Producto</TableHead>
                            <TableHead class="text-center">Cantidad</TableHead>
                            <TableHead class="text-end">Monto</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(item, i) in top_productos" :key="item.producto_id">
                            <TableCell class="text-muted-foreground text-xs">{{ i + 1 }}</TableCell>
                            <TableCell class="font-medium">{{ item.producto?.nombre }}</TableCell>
                            <TableCell class="text-center">{{ item.total_cantidad }}</TableCell>
                            <TableCell class="text-end">S/ {{ Number(item.total_monto).toFixed(2) }}</TableCell>
                        </TableRow>
                        <TableRow v-if="!top_productos.length">
                            <TableCell colspan="4" class="text-center text-muted-foreground py-4">Sin datos</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Por método de pago -->
            <Card class="p-4">
                <div class="flex items-center gap-2 mb-3 font-semibold">
                    <CreditCard class="h-4 w-4 text-primary" />
                    Por Método de Pago
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Método</TableHead>
                            <TableHead class="text-center">Ventas</TableHead>
                            <TableHead class="text-end">Monto</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in por_metodo" :key="item.metodo_pago">
                            <TableCell class="font-medium">{{ metodoPagoLabel[item.metodo_pago] ?? item.metodo_pago }}</TableCell>
                            <TableCell class="text-center">{{ item.cantidad }}</TableCell>
                            <TableCell class="text-end">S/ {{ Number(item.monto).toFixed(2) }}</TableCell>
                        </TableRow>
                        <TableRow v-if="!por_metodo.length">
                            <TableCell colspan="3" class="text-center text-muted-foreground py-4">Sin datos</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Por sede (solo admin) -->
            <Card v-if="es_admin" class="p-4">
                <div class="flex items-center gap-2 mb-3 font-semibold">
                    <MapPin class="h-4 w-4 text-primary" />
                    Por Sede
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sede</TableHead>
                            <TableHead class="text-center">Ventas</TableHead>
                            <TableHead class="text-end">Monto</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in por_sede" :key="item.sede_id">
                            <TableCell class="font-medium">{{ item.sede?.nombre }}</TableCell>
                            <TableCell class="text-center">{{ item.cantidad }}</TableCell>
                            <TableCell class="text-end">S/ {{ Number(item.monto).toFixed(2) }}</TableCell>
                        </TableRow>
                        <TableRow v-if="!por_sede.length">
                            <TableCell colspan="3" class="text-center text-muted-foreground py-4">Sin datos</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Ventas por día -->
            <Card class="p-4">
                <div class="flex items-center gap-2 mb-3 font-semibold">
                    <TrendingUp class="h-4 w-4 text-primary" />
                    Ventas por Día
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Fecha</TableHead>
                            <TableHead class="text-center">Ventas</TableHead>
                            <TableHead class="text-end">Monto</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in ventas_dia" :key="item.fecha">
                            <TableCell>{{ item.fecha }}</TableCell>
                            <TableCell class="text-center">{{ item.cantidad }}</TableCell>
                            <TableCell class="text-end">S/ {{ Number(item.monto).toFixed(2) }}</TableCell>
                        </TableRow>
                        <TableRow v-if="!ventas_dia.length">
                            <TableCell colspan="3" class="text-center text-muted-foreground py-4">Sin datos en el período</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>
        </div>
    </div>
</template>
