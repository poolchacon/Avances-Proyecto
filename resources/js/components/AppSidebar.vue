<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Store, Tags, Users, ShoppingBag, Package, ClipboardList, UserCog, BarChart2 } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import sedes from '@/routes/sedes/index.js';
import categorias from '@/routes/categorias/index.js';
import clientes from '@/routes/clientes/index.js';
import productos from '@/routes/productos/index.js';
import usuarios from '@/routes/usuarios/index.js';
import stock from '@/routes/stock/index.js';

const page = usePage();
const user = computed(() => (page.props.auth as any).user);
const isAdmin = computed(() => user.value?.rol === 'ADMINISTRADOR');

// Items disponibles para VENDEDOR
const vendedorItems: NavItem[] = [
    { title: 'Ventas',     href: '/ventas',            icon: ShoppingBag  },
    { title: 'Historial',  href: '/ventas/historial',  icon: ClipboardList },
    { title: 'Reportes',   href: '/reportes',          icon: BarChart2    },
    { title: 'Stock',      href: stock.index(),        icon: Package      },
];

// Items exclusivos de ADMINISTRADOR (se agregan encima)
const adminItems: NavItem[] = [
    { title: 'Dashboard',  href: dashboard(),          icon: LayoutGrid   },
    { title: 'Ventas',     href: '/ventas',            icon: ShoppingBag  },
    { title: 'Historial',  href: '/ventas/historial',  icon: ClipboardList },
    { title: 'Reportes',   href: '/reportes',          icon: BarChart2    },
    { title: 'Stock',      href: stock.index(),        icon: Package      },
    { title: 'Sedes',      href: sedes.index(),        icon: Store        },
    { title: 'Categorías', href: categorias.index(),   icon: Tags         },
    { title: 'Clientes',   href: clientes.index(),     icon: Users        },
    { title: 'Productos',  href: productos.index(),    icon: ShoppingBag  },
    { title: 'Usuarios',   href: usuarios.index(),     icon: UserCog      },
];

const mainNavItems = computed(() => isAdmin.value ? adminItems : vendedorItems);

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="isAdmin ? dashboard() : '/ventas'">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
