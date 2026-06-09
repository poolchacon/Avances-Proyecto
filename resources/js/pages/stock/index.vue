<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import stock from '@/routes/stock/index.js';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { Plus, Search, AlertTriangle } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import {
    Table, TableBody, TableCell, TableHead,
    TableHeader, TableRow,
} from '@/components/ui/table';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Stock', href: stock.index() },
        ],
    },
});

const props = defineProps<{
    collection: any;
    sedes: any;
    search: string | null;
    sede_id: number | null;
}>();
</script>

<template>
    <Head title="Stock" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="absolute top-1.5 right-4 flex items-center gap-2">
            <Button>
                <Link :href="stock.create()" class="flex gap-1">
                    <Plus /> Registrar Movimiento
                </Link>
            </Button>
        </div>

        <div class="mb-0">
            <Form method="GET" :href="stock.index()" class="flex gap-2 flex-wrap">
                <Input name="search" :default-value="search ?? ''" placeholder="Buscar producto..." class="max-w-sm" />
                <select name="sede_id"
                    class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                    <option value="">Todas las sedes</option>
                    <option v-for="sede in sedes.data ?? sedes" :key="sede.id"
                        :value="sede.id"
                        :selected="sede_id === sede.id">
                        {{ sede.nombre }}
                    </option>
                </select>
                <Button variant="secondary"><Search class="mr-2 h-4 w-4" /> Buscar</Button>
            </Form>
        </div>

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Producto</TableHead>
                    <TableHead>Sede</TableHead>
                    <TableHead class="text-center">Cantidad</TableHead>
                    <TableHead class="text-center">Stock Mínimo</TableHead>
                    <TableHead class="text-center">Estado</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="item in collection.data" :key="item.id"
                    :class="item.bajo_minimo ? 'bg-red-50 dark:bg-red-950/20' : ''">
                    <TableCell>
                        <div class="flex items-center gap-2">
                            <AlertTriangle v-if="item.bajo_minimo" class="h-4 w-4 text-red-500" />
                            {{ item.producto }}
                        </div>
                    </TableCell>
                    <TableCell>{{ item.sede }}</TableCell>
                    <TableCell class="text-center font-semibold">
                        <span :class="item.bajo_minimo ? 'text-red-600' : 'text-green-600'">
                            {{ item.cantidad_disponible }}
                        </span>
                    </TableCell>
                    <TableCell class="text-center text-muted-foreground">{{ item.stock_minimo }}</TableCell>
                    <TableCell class="text-center">
                        <Badge :variant="item.bajo_minimo ? 'destructive' : 'default'">
                            {{ item.bajo_minimo ? 'Bajo mínimo' : 'OK' }}
                        </Badge>
                    </TableCell>
                </TableRow>
                <TableRow v-if="!collection.data.length">
                    <TableCell colspan="5" class="text-center text-muted-foreground py-8">
                        No hay registros de stock aún. Registra movimientos para verlos aquí.
                    </TableCell>
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
