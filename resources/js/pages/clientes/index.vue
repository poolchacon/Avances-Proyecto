<script setup lang="ts">
import { Head, Link, router, Form } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import clientes from '@/routes/clientes/index.js';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { Plus, Search, Pencil, Trash } from 'lucide-vue-next';
import { Cliente } from '@/types/index.js';
import {
    Table, TableBody, TableCell, TableHead,
    TableHeader, TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Clientes', href: clientes.index() },
        ],
    },
});

const props = defineProps<{
    collection: Cliente;
    search: string | null;
}>();

const onDelete = (id: number) => {
    if (window.confirm('¿Está seguro de eliminar este cliente?')) {
        router.delete(clientes.destroy({ cliente: id }));
    }
};
</script>

<template>
    <Head title="Clientes" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="absolute top-1.5 right-4 flex items-center gap-2">
            <Button>
                <Link :href="clientes.create()" class="flex gap-1">
                    <Plus /> Nuevo Cliente
                </Link>
            </Button>
        </div>

        <div class="mb-0">
            <Form method="GET" :href="clientes.index()" class="flex gap-2">
                <Input name="search" :default-value="search ?? ''" placeholder="Buscar cliente..." class="max-w-sm" />
                <Button variant="secondary"><Search class="mr-2 h-4 w-4" /> Buscar</Button>
            </Form>
        </div>

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Nombre</TableHead>
                    <TableHead>Teléfono</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Estado</TableHead>
                    <TableHead class="text-end">Acciones</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="item in collection.data" :key="item.id">
                    <TableCell class="font-medium">{{ item.nombre }}</TableCell>
                    <TableCell>{{ item.telefono }}</TableCell>
                    <TableCell>{{ item.email ?? '—' }}</TableCell>
                    <TableCell>
                        <Badge :variant="item.estado === 'A' ? 'default' : 'secondary'">
                            {{ item.estado === 'A' ? 'Activo' : 'Inactivo' }}
                        </Badge>
                    </TableCell>
                    <TableCell class="text-end">
                        <Button variant="secondary" size="sm" class="mr-2" title="Editar">
                            <Link :href="clientes.edit(item.id)"><Pencil /></Link>
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
