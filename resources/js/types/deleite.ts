export type Sede = {
    meta: any;
    id: number;
    nombre: string;
    direccion: string | null;
    telefono: string | null;
    estado: 'A' | 'I';
};

export type Categoria = {
    meta: any;
    id: number;
    nombre: string;
    descripcion: string | null;
    estado: 'A' | 'I';
};

export type Cliente = {
    meta: any;
    id: number;
    nombre: string;
    telefono: string;
    direccion: string | null;
    email: string | null;
    referencia: string | null;
    estado: 'A' | 'I';
};

export type Producto = {
    meta: any;
    id: number;
    nombre: string;
    descripcion: string | null;
    precio: number;
    categoria_id: number;
    categoria: string | null;
    estado: 'A' | 'I';
    imagen: string;
};

export type Stock = {
    meta: any;
    id: number;
    producto_id: number;
    sede_id: number;
    cantidad_disponible: number;
    stock_minimo: number;
    producto: string | null;
    sede: string | null;
};

export type Venta = {
    meta: any;
    id: number;
    user_id: number;
    cliente_id: number | null;
    sede_id: number;
    fecha_venta: string;
    tipo_pedido: 'SALON' | 'DELIVERY' | 'LLEVAR';
    metodo_pago: 'EFECTIVO' | 'YAPE' | 'PLIN' | 'TARJETA' | 'MIXTO';
    monto_efectivo: number;
    monto_yape: number;
    monto_plin: number;
    monto_tarjeta: number;
    subtotal: number;
    descuento: number;
    total: number;
    estado: 'COMPLETADA' | 'ANULADA';
    observaciones: string | null;
    cliente: string | null;
    sede: string | null;
    vendedor: string | null;
};
