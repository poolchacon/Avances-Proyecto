<script setup lang="ts">
import { Head, Link, router, Form } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import productos from '@/routes/productos/index.js';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { Plus, Search, Pencil, Trash } from 'lucide-vue-next';
import { Producto } from '@/types/index.js';
import {
    Table, TableBody, TableCell, TableHead,
    TableHeader, TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Productos', href: productos.index() },
        ],
    },
});

const props = defineProps<{
    collection: Producto;
    search: string | null;
}>();

const onDelete = (id: number) => {
    if (window.confirm('¿Está seguro de eliminar este producto?')) {
        router.delete(productos.destroy({ producto: id }));
    }
};

const formatPrecio = (precio: number) =>
    new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(precio);
</script>

<template>
    <Head title="Productos" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="absolute top-1.5 right-4 flex items-center gap-2">
            <Button>
                <Link :href="productos.create()" class="flex gap-1">
                    <Plus /> Nuevo Producto
                </Link>
            </Button>
        </div>

        <div class="mb-0">
            <Form method="GET" :href="productos.index()" class="flex gap-2">
                <Input name="search" :default-value="search ?? ''" placeholder="Buscar producto..." class="max-w-sm" />
                <Button variant="secondary"><Search class="mr-2 h-4 w-4" /> Buscar</Button>
            </Form>
        </div>

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Imagen</TableHead>
                    <TableHead>Nombre</TableHead>
                    <TableHead>Categoría</TableHead>
                    <TableHead>Precio</TableHead>
                    <TableHead>Estado</TableHead>
                    <TableHead class="text-end">Acciones</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="item in collection.data" :key="item.id">
                    <TableCell>
                        <img :src="item.imagen ? item.imagen : '/apple-touch-icon.png'"
                            class="aspect-square h-10 w-10 rounded bg-background object-cover" />
                    </TableCell>
                    <TableCell class="font-medium">{{ item.nombre }}</TableCell>
                    <TableCell>{{ item.categoria }}</TableCell>
                    <TableCell>{{ formatPrecio(item.precio) }}</TableCell>
                    <TableCell>
                        <Badge :variant="item.estado === 'A' ? 'default' : 'secondary'">
                            {{ item.estado === 'A' ? 'Activo' : 'Inactivo' }}
                        </Badge>
                    </TableCell>
                    <TableCell class="text-end">
                        <Button variant="secondary" size="sm" class="mr-2" title="Editar">
                            <Link :href="productos.edit(item.id)"><Pencil /></Link>
                        </Button>
                        <Button variant="destructive" size="sm" title="Eliminar" @click="onDelete(item.id)">
                            <Trash />
                        </Button>
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
