<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ventas from '@/routes/ventas/index.js';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Plus, Minus, Trash2, ShoppingCart, Save, History } from 'lucide-vue-next';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Ventas (POS)' },
        ],
    },
});

const props = defineProps<{
    productos: any;
    clientes: any;
    sedes: any;
    sede_activa_id: number | null;
    stock: Record<number, number>;
}>();

const page = usePage();
const user = (page.props.auth as any).user;

// Usa sede_activa_id (admin con selector) o sede_id del usuario (vendedor)
const sedeId        = ref(String(props.sede_activa_id ?? (user?.sede_id ?? '')));
const clienteId     = ref('');
const tipoPedido    = ref('LLEVAR');
const metodoPago    = ref('EFECTIVO');
const descuento     = ref(0);
const observaciones = ref('');
const montoEfectivo = ref(0);
const montoYape     = ref(0);
const montoPlin     = ref(0);
const montoTarjeta  = ref(0);
const busqueda      = ref('');
const categoriaFiltro = ref('');
const processing    = ref(false);
const errors        = ref<Record<string, string>>({});

interface CartItem { producto_id: number; nombre: string; precio: number; cantidad: number; }
const carrito = ref<CartItem[]>([]);

const listaProductos = computed(() => props.productos.data ?? props.productos);

const categorias = computed(() => {
    const cats = new Set<string>();
    listaProductos.value.forEach((p: any) => { if (p.categoria) cats.add(p.categoria); });
    return Array.from(cats);
});

const productosFiltrados = computed(() =>
    listaProductos.value.filter((p: any) => {
        const ok1 = !busqueda.value || p.nombre.toLowerCase().includes(busqueda.value.toLowerCase());
        const ok2 = !categoriaFiltro.value || p.categoria === categoriaFiltro.value;
        return ok1 && ok2;
    })
);

const agregar = (p: any) => {
    const ex = carrito.value.find(i => i.producto_id === p.id);
    if (ex) { ex.cantidad++; }
    else { carrito.value.push({ producto_id: p.id, nombre: p.nombre, precio: parseFloat(p.precio), cantidad: 1 }); }
};

const quitarUno = (item: CartItem) => {
    if (item.cantidad > 1) { item.cantidad--; }
    else { carrito.value = carrito.value.filter(i => i.producto_id !== item.producto_id); }
};

const subtotal = computed(() => carrito.value.reduce((s, i) => s + i.precio * i.cantidad, 0));
const total    = computed(() => Math.max(0, subtotal.value - descuento.value));

const confirmar = () => {
    if (!carrito.value.length) { alert('Agrega al menos un producto.'); return; }
    if (!sedeId.value) { alert('Selecciona una sede.'); return; }
    processing.value = true;
    router.post(ventas.store(), {
        sede_id: sedeId.value, cliente_id: clienteId.value || null,
        tipo_pedido: tipoPedido.value, metodo_pago: metodoPago.value,
        monto_efectivo: montoEfectivo.value, monto_yape: montoYape.value,
        monto_plin: montoPlin.value, monto_tarjeta: montoTarjeta.value,
        descuento: descuento.value, observaciones: observaciones.value,
        items: carrito.value.map(i => ({ producto_id: i.producto_id, cantidad: i.cantidad, precio: i.precio })),
    }, {
        onError: (e) => { errors.value = e; processing.value = false; },
        onSuccess: () => {
            carrito.value = [];
            descuento.value = 0; observaciones.value = '';
            montoEfectivo.value = 0; montoYape.value = 0; montoPlin.value = 0; montoTarjeta.value = 0;
            processing.value = false;
        },
    });
};
</script>

<template>
    <Head title="Ventas — POS" />

    <!-- Botón historial arriba a la derecha -->
    <div class="absolute top-1.5 right-4 flex items-center gap-2">
        <Button variant="outline" size="sm" as-child>
            <Link href="/ventas/historial"><History class="h-4 w-4 mr-1" /> Historial</Link>
        </Button>
        <Button v-if="user?.rol === 'ADMINISTRADOR'" variant="ghost" size="sm" as-child>
            <Link href="/selector-sede">Cambiar Sede</Link>
        </Button>
    </div>

    <div class="flex h-full gap-4 overflow-hidden p-0">

        <!-- Catálogo (izquierda) -->
        <div class="flex flex-col gap-3 flex-1 overflow-y-auto px-4 pt-2 pb-4">
            <!-- Búsqueda -->
            <Input v-model="busqueda" placeholder="🔍 Buscar producto..." class="max-w-sm" />

            <!-- Chips de categoría -->
            <div class="flex flex-wrap gap-1">
                <button type="button" @click="categoriaFiltro = ''"
                    :class="['text-xs px-3 py-1 rounded-full border font-medium transition-colors',
                        !categoriaFiltro
                            ? 'bg-primary text-primary-foreground border-primary'
                            : 'border-border hover:bg-accent']">
                    Todos
                </button>
                <button v-for="cat in categorias" :key="cat" type="button" @click="categoriaFiltro = cat"
                    :class="['text-xs px-3 py-1 rounded-full border font-medium transition-colors',
                        categoriaFiltro === cat
                            ? 'bg-primary text-primary-foreground border-primary'
                            : 'border-border hover:bg-accent']">
                    {{ cat }}
                </button>
            </div>

            <!-- Grid de productos -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                <button v-for="p in productosFiltrados" :key="p.id"
                    type="button" @click="agregar(p)"
                    class="relative text-left p-3 rounded-lg border border-border bg-card hover:bg-accent hover:border-primary transition-all cursor-pointer">
                    <!-- Badge de stock -->
                    <span v-if="props.stock[p.id] !== undefined"
                        :class="['absolute top-1.5 right-1.5 text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none',
                            props.stock[p.id] === 0
                                ? 'bg-destructive/20 text-destructive'
                                : props.stock[p.id] <= 3
                                    ? 'bg-yellow-500/20 text-yellow-500'
                                    : 'bg-green-500/15 text-green-500']">
                        {{ props.stock[p.id] === 0 ? 'Agotado' : props.stock[p.id] }}
                    </span>
                    <div class="font-semibold text-sm leading-tight pr-8">{{ p.nombre }}</div>
                    <div class="text-xs text-muted-foreground mt-0.5">{{ p.categoria }}</div>
                    <div class="font-bold text-primary mt-2 text-sm">S/ {{ Number(p.precio).toFixed(2) }}</div>
                </button>
            </div>
        </div>

        <!-- Panel del carrito (derecha) -->
        <div class="w-72 flex flex-col gap-2 border-l bg-card overflow-y-auto px-3 py-2">

            <!-- Sede (solo admin) -->
            <div v-if="user?.rol === 'ADMINISTRADOR'" class="grid gap-1">
                <Label class="text-xs font-semibold">Sede</Label>
                <select v-model="sedeId"
                    class="flex h-8 w-full rounded-md border border-input bg-transparent px-2 text-xs shadow-sm">
                    <option value="">— Seleccione sede —</option>
                    <option v-for="s in sedes.data ?? sedes" :key="s.id" :value="String(s.id)">{{ s.nombre }}</option>
                </select>
                <p v-if="errors.sede_id" class="text-xs text-destructive">{{ errors.sede_id }}</p>
            </div>

            <!-- Cliente -->
            <div class="grid gap-1">
                <Label class="text-xs font-semibold">Cliente (opcional)</Label>
                <select v-model="clienteId"
                    class="flex h-8 w-full rounded-md border border-input bg-transparent px-2 text-xs shadow-sm">
                    <option value="">Sin cliente</option>
                    <option v-for="c in clientes.data ?? clientes" :key="c.id" :value="String(c.id)">{{ c.nombre }}</option>
                </select>
            </div>

            <!-- Tipo de pedido -->
            <div class="grid gap-1">
                <Label class="text-xs font-semibold">Tipo de pedido</Label>
                <div class="flex gap-1">
                    <button type="button" v-for="tipo in ['SALON', 'LLEVAR', 'DELIVERY']" :key="tipo"
                        @click="tipoPedido = tipo"
                        :class="['flex-1 text-xs py-1 rounded border transition-colors font-medium',
                            tipoPedido === tipo
                                ? 'bg-primary text-primary-foreground border-primary'
                                : 'border-border hover:bg-accent']">
                        {{ tipo === 'SALON' ? 'Salón' : tipo === 'LLEVAR' ? 'Llevar' : 'Delivery' }}
                    </button>
                </div>
            </div>

            <!-- Separador -->
            <div class="border-t pt-2">
                <div class="flex items-center gap-1 mb-2 text-xs font-semibold text-muted-foreground uppercase tracking-wide">
                    <ShoppingCart class="h-3.5 w-3.5" /> Carrito
                </div>

                <div v-if="!carrito.length" class="text-center text-muted-foreground text-xs py-4">
                    Haz clic en un producto para agregarlo
                </div>

                <div class="flex flex-col gap-1">
                    <div v-for="item in carrito" :key="item.producto_id"
                        class="flex items-center gap-1 p-1.5 rounded-md bg-muted/50 text-xs">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium truncate">{{ item.nombre }}</div>
                            <div class="text-muted-foreground">S/ {{ item.precio.toFixed(2) }}</div>
                        </div>
                        <div class="flex items-center gap-0.5">
                            <button type="button" @click="quitarUno(item)"
                                class="h-5 w-5 rounded bg-muted flex items-center justify-center hover:bg-destructive/20">
                                <Minus class="h-3 w-3" />
                            </button>
                            <span class="w-5 text-center font-bold">{{ item.cantidad }}</span>
                            <button type="button" @click="item.cantidad++"
                                class="h-5 w-5 rounded bg-muted flex items-center justify-center hover:bg-primary/20">
                                <Plus class="h-3 w-3" />
                            </button>
                        </div>
                        <span class="w-14 text-right font-semibold">S/ {{ (item.precio * item.cantidad).toFixed(2) }}</span>
                        <button type="button" @click="carrito = carrito.filter(i => i.producto_id !== item.producto_id)"
                            class="text-destructive hover:opacity-70 ml-0.5">
                            <Trash2 class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Totales -->
            <div class="border-t pt-2 space-y-1">
                <div class="flex justify-between text-xs text-muted-foreground">
                    <span>Subtotal</span><span>S/ {{ subtotal.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between items-center gap-2 text-xs text-muted-foreground">
                    <span>Descuento</span>
                    <Input v-model.number="descuento" type="number" step="0.5" min="0" class="h-6 w-20 text-right text-xs" />
                </div>
                <div class="flex justify-between font-bold text-base pt-1">
                    <span>TOTAL</span><span class="text-primary">S/ {{ total.toFixed(2) }}</span>
                </div>
            </div>

            <!-- Método de pago -->
            <div class="grid gap-1">
                <Label class="text-xs font-semibold">Método de pago</Label>
                <select v-model="metodoPago"
                    class="flex h-8 w-full rounded-md border border-input bg-transparent px-2 text-xs shadow-sm">
                    <option value="EFECTIVO">Efectivo</option>
                    <option value="YAPE">Yape</option>
                    <option value="PLIN">Plin</option>
                    <option value="TARJETA">Tarjeta</option>
                    <option value="MIXTO">Mixto</option>
                </select>
            </div>
            <div v-if="metodoPago === 'EFECTIVO' || metodoPago === 'MIXTO'" class="grid gap-1">
                <Label class="text-xs">Efectivo</Label>
                <Input v-model.number="montoEfectivo" type="number" step="0.01" min="0" class="h-7 text-xs" />
            </div>
            <div v-if="metodoPago === 'YAPE' || metodoPago === 'MIXTO'" class="grid gap-1">
                <Label class="text-xs">Yape</Label>
                <Input v-model.number="montoYape" type="number" step="0.01" min="0" class="h-7 text-xs" />
            </div>
            <div v-if="metodoPago === 'PLIN' || metodoPago === 'MIXTO'" class="grid gap-1">
                <Label class="text-xs">Plin</Label>
                <Input v-model.number="montoPlin" type="number" step="0.01" min="0" class="h-7 text-xs" />
            </div>
            <div v-if="metodoPago === 'TARJETA' || metodoPago === 'MIXTO'" class="grid gap-1">
                <Label class="text-xs">Tarjeta</Label>
                <Input v-model.number="montoTarjeta" type="number" step="0.01" min="0" class="h-7 text-xs" />
            </div>

            <!-- Observaciones -->
            <div class="grid gap-1">
                <Label class="text-xs font-semibold">Observaciones</Label>
                <Input v-model="observaciones" placeholder="Ej: Sin azúcar, extra dulce..." class="h-7 text-xs" />
            </div>

            <p v-if="errors.items" class="text-xs text-destructive">{{ errors.items }}</p>

            <!-- Botón confirmar -->
            <Button @click="confirmar" :disabled="processing || !carrito.length" class="w-full mt-1">
                <Save class="h-4 w-4 mr-1" />
                {{ processing ? 'Procesando...' : `Procesar Venta · S/ ${total.toFixed(2)}` }}
            </Button>
        </div>
    </div>
</template>
