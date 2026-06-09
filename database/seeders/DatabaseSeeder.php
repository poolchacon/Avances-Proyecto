<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Sede;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\StockSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sedes reales
        $sedes = [
            ['nombre' => 'Sede Ejército 999', 'direccion' => 'Av. Ejercito 999',  'telefono' => '945337015', 'estado' => 'A'],
            ['nombre' => 'Ovalo Grau',         'direccion' => 'Ovalo Grau',        'telefono' => '949377442', 'estado' => 'A'],
            ['nombre' => 'Sede Moche',         'direccion' => 'Av. La marina',     'telefono' => '945337015', 'estado' => 'A'],
            ['nombre' => 'UPAO',               'direccion' => 'AV...',             'telefono' => null,        'estado' => 'I'],
        ];
        foreach ($sedes as $s) {
            Sede::create($s);
        }

        // Categorías reales
        $categorias = [
            ['nombre' => 'Cuchareable',        'descripcion' => 'Postres cuchareable (cheesecakes, tortas 3L, chocos)',   'estado' => 'A'],
            ['nombre' => 'Alfajor',            'descripcion' => 'Alfajores artesanales',                                  'estado' => 'A'],
            ['nombre' => 'Chocolate',          'descripcion' => 'Tortas y postres de chocolate',                          'estado' => 'A'],
            ['nombre' => 'Dúo Promo 2x30',     'descripcion' => 'Promociones 2 productos por S/. 30',                    'estado' => 'A'],
            ['nombre' => 'Cremoladas',         'descripcion' => 'Cremoladas de sabores naturales',                        'estado' => 'A'],
            ['nombre' => 'Cuchareable Pequeño','descripcion' => 'Cheesecakes en porción pequeña',                         'estado' => 'A'],
            ['nombre' => 'Bebidas y Otros',    'descripcion' => 'Bebidas y productos adicionales',                        'estado' => 'A'],
        ];
        foreach ($categorias as $c) {
            Categoria::create($c);
        }

        // IDs de categorías (creadas en orden: 1=Cuchareable … 7=Bebidas)
        // Usuarios reales (contraseña 'password' para todos)
        $users = [
            ['name' => 'Administrador Sistema', 'email' => 'admin@deleite.com',      'sede_id' => 1, 'rol' => 'ADMINISTRADOR', 'estado' => 'A'],
            ['name' => 'Zoila Rosa',             'email' => 'zoila@deleite.com',      'sede_id' => 1, 'rol' => 'ADMINISTRADOR', 'estado' => 'A'],
            ['name' => 'Kenyerlin Medina',       'email' => 'kenyerlin@deleite.com',  'sede_id' => 2, 'rol' => 'VENDEDOR',      'estado' => 'I'],
            ['name' => 'Olenka',                 'email' => 'olenka@deleite.com',     'sede_id' => 1, 'rol' => 'VENDEDOR',      'estado' => 'I'],
            ['name' => 'Yoselin',                'email' => 'yoselin@deleite.com',    'sede_id' => 3, 'rol' => 'ADMINISTRADOR', 'estado' => 'A'],
            ['name' => 'Keyla Ponce Sanchez',    'email' => 'keyla@deleite.com',      'sede_id' => 3, 'rol' => 'VENDEDOR',      'estado' => 'I'],
            ['name' => 'Zonaly Ponce Sanchez',   'email' => 'zonaly@deleite.com',     'sede_id' => 1, 'rol' => 'VENDEDOR',      'estado' => 'A'],
            ['name' => 'Angie S.T.',             'email' => 'angie@deleite.com',      'sede_id' => 3, 'rol' => 'ADMINISTRADOR', 'estado' => 'A'],
            ['name' => 'Ivone Gutierrez',        'email' => 'ivone@deleite.com',      'sede_id' => 2, 'rol' => 'VENDEDOR',      'estado' => 'A'],
            ['name' => 'Ivonne',                 'email' => 'ivonne@deleite.com',     'sede_id' => 3, 'rol' => 'VENDEDOR',      'estado' => 'A'],
            ['name' => 'Kiara Jc',               'email' => 'kiara@deleite.com',      'sede_id' => 1, 'rol' => 'VENDEDOR',      'estado' => 'A'],
            ['name' => 'Danny Esquerre',         'email' => 'danny@deleite.com',      'sede_id' => 3, 'rol' => 'VENDEDOR',      'estado' => 'I'],
        ];
        foreach ($users as $u) {
            User::create(array_merge($u, ['password' => Hash::make('password')]));
        }

        // Productos reales (categoria_id empieza en 1 = Cuchareable segun el orden creado)
        $productos = [
            // Cuchareable (id=1)
            ['nombre' => 'Cheesecake fresa',          'descripcion' => 'Cheesecake cremoso con fresa',       'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Cheesecake maracuyá',        'descripcion' => 'Cheesecake cremoso con maracuyá',    'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Cheesecake arándano',        'descripcion' => 'Cheesecake cremoso con arándano',    'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Cheesecake oreo',            'descripcion' => 'Cheesecake cremoso con oreo',        'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Cheesecake pistacho',        'descripcion' => 'Cheesecake cremoso con pistacho',    'precio' => 10.00, 'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Tres leches fresa',          'descripcion' => 'Tres leches con fresa',              'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Tres leches chocolate',      'descripcion' => 'Tres leches de chocolate',           'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Tres leches Mocca',          'descripcion' => 'Tres leches de Mocca con café',      'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Choco Menta',                'descripcion' => 'Postre de chocolate con menta',      'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Choco lúcuma',               'descripcion' => 'Postre de chocolate con lúcuma',     'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Crocante de mango',          'descripcion' => 'Postre cuchareable crocante de mango','precio' => 8.00, 'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Chocoteja',                  'descripcion' => 'Postre cuchareable de chocoteja',    'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Crocante fresa',             'descripcion' => 'Postre cuchareable crocante de fresa','precio' => 8.00, 'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Maracumango',                'descripcion' => 'Postre cuchareable de maracuyá con mango','precio' => 8.00,'categoria_id' => 1,'estado' => 'A'],
            ['nombre' => 'Pistarosa',                  'descripcion' => 'Postre cuchareable de pistacho y rosa','precio' => 8.00,'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Tres leches durazno',        'descripcion' => 'Tres leches con durazno',            'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Tres leches manjar',         'descripcion' => 'Tres leches con manjar',             'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Selva negra',                'descripcion' => 'Postre cuchareable selva negra',     'precio' => 8.00,  'categoria_id' => 1, 'estado' => 'A'],
            ['nombre' => 'Tres leches de arándanos',   'descripcion' => 'Contiene Mermelada de arándano, Chantilly, Keke de tres leches','precio' => 8.00,'categoria_id' => 1,'estado' => 'A'],
            // Alfajor (id=2)
            ['nombre' => 'Alfajor clásico',            'descripcion' => 'Alfajor tradicional',                'precio' => 8.00,  'categoria_id' => 2, 'estado' => 'A'],
            ['nombre' => 'Alfajor relleno de fresa',   'descripcion' => 'Alfajor con relleno de fresa',       'precio' => 8.00,  'categoria_id' => 2, 'estado' => 'A'],
            ['nombre' => 'Alfajor Pistacho',           'descripcion' => 'Alfajor con pistacho',               'precio' => 12.00, 'categoria_id' => 2, 'estado' => 'A'],
            ['nombre' => 'Alfajor pote de chocolate',  'descripcion' => 'Alfajor en pote de chocolate',       'precio' => 4.00,  'categoria_id' => 2, 'estado' => 'A'],
            // Chocolate (id=3)
            ['nombre' => 'Chocolate húmedo',           'descripcion' => 'Torta húmeda relleno de manjar y fudge','precio' => 8.00,'categoria_id' => 3,'estado' => 'A'],
            ['nombre' => 'Volcán chocolate',           'descripcion' => 'Torta húmeda de chocolate, el centro relleno de fudge','precio' => 8.00,'categoria_id' => 3,'estado' => 'A'],
            ['nombre' => 'Choco oreo',                 'descripcion' => 'Torta húmeda de chocolate rellena de fudge ganache de oreo','precio' => 8.00,'categoria_id' => 3,'estado' => 'A'],
            ['nombre' => 'Nutella',                    'descripcion' => 'Torta húmeda de chocolate, crema de nutella y ganache de nutella','precio' => 8.00,'categoria_id' => 3,'estado' => 'A'],
            ['nombre' => 'Carrot cake',                'descripcion' => 'Torta zanahoria relleno de manjar y frosting de queso','precio' => 8.00,'categoria_id' => 3,'estado' => 'A'],
            ['nombre' => 'Red velvet',                 'descripcion' => 'Relleno de frosting de queso',        'precio' => 8.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Terremoto de lúcuma',        'descripcion' => 'Muss de lúcuma, brownies de chocolate bañados con fudge de olla','precio' => 8.00,'categoria_id' => 3,'estado' => 'A'],
            ['nombre' => 'Pie de limón',               'descripcion' => 'Crema de limón, galleta, merengue',  'precio' => 8.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Torta chocolate Grande',     'descripcion' => 'Torta de chocolate 5/8',              'precio' => 8.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Alfajor pote',               'descripcion' => 'Alfajor en pote',                    'precio' => 4.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Keke zanahoria',             'descripcion' => 'Keke de zanahoria',                  'precio' => 4.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Tres leches cuadrado',       'descripcion' => 'Tres leches en porción cuadrada',    'precio' => 4.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Torta de chocolate Pequeña', 'descripcion' => 'Porción de torta de chocolate',      'precio' => 4.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Gelatina',                   'descripcion' => 'Gelatina de sabores',                'precio' => 2.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Gelatina payaso',            'descripcion' => 'Gelatina payaso de colores',         'precio' => 2.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Leche asada',                'descripcion' => 'Postre de leche asada',              'precio' => 2.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Pudín',                      'descripcion' => 'Pudín casero',                       'precio' => 2.00,  'categoria_id' => 3, 'estado' => 'A'],
            ['nombre' => 'Choco sublime',              'descripcion' => '',                                   'precio' => 8.00,  'categoria_id' => 3, 'estado' => 'A'],
            // Dúo Promo 2x30 (id=4)
            ['nombre' => 'Dúo Clásica - Chocolate c/ 3 leches', 'descripcion' => 'Promoción 2x30','precio' => 15.00,'categoria_id' => 4,'estado' => 'A'],
            ['nombre' => 'Dúo Sublime - Choco Sublime c/ 3 leches','descripcion' => 'Promoción 2x30','precio' => 15.00,'categoria_id' => 4,'estado' => 'A'],
            ['nombre' => 'Dúo Pistacho - Choco Pistacho c/ 3 leches','descripcion' => 'Promoción 2x30','precio' => 15.00,'categoria_id' => 4,'estado' => 'A'],
            ['nombre' => 'Dúo Oreo - Choco Oreo c/ 3 leches','descripcion' => 'Promoción 2x30','precio' => 15.00,'categoria_id' => 4,'estado' => 'A'],
            // Cremoladas (id=5)
            ['nombre' => 'Cremolada vaso de 5',        'descripcion' => 'Todos los sabores',                  'precio' => 5.00,  'categoria_id' => 5, 'estado' => 'A'],
            ['nombre' => 'Cremolada vaso de 8',        'descripcion' => 'Todos los sabores',                  'precio' => 8.00,  'categoria_id' => 5, 'estado' => 'A'],
            ['nombre' => 'Cremolada vaso de 11',       'descripcion' => 'Todos los sabores',                  'precio' => 11.00, 'categoria_id' => 5, 'estado' => 'A'],
            // Cuchareable Pequeño (id=6)
            ['nombre' => 'Cheesecake arándano pequeño','descripcion' => 'Cheesecake de arándano porción pequeña','precio' => 4.00,'categoria_id' => 6,'estado' => 'A'],
            ['nombre' => 'Cheesecake fresa pequeño',   'descripcion' => 'Cheesecake de fresa porción pequeña','precio' => 4.00,  'categoria_id' => 6, 'estado' => 'A'],
            ['nombre' => 'Cheesecake maracuyá pequeño','descripcion' => 'Cheesecake de maracuyá porción pequeña','precio' => 4.00,'categoria_id' => 6,'estado' => 'A'],
            ['nombre' => 'Cheesecake oreo pequeño',    'descripcion' => 'Cheesecake de oreo porción pequeña', 'precio' => 4.00,  'categoria_id' => 6, 'estado' => 'A'],
            // Bebidas y Otros (id=7)
            ['nombre' => 'Agua Mineral',               'descripcion' => 'Agua mineral fría',                  'precio' => 2.00,  'categoria_id' => 7, 'estado' => 'A'],
            ['nombre' => 'Café',                       'descripcion' => 'Café caliente',                      'precio' => 3.00,  'categoria_id' => 7, 'estado' => 'A'],
            ['nombre' => 'Coca Cola',                  'descripcion' => 'Coca Cola 500ml',                    'precio' => 3.50,  'categoria_id' => 7, 'estado' => 'A'],
            ['nombre' => 'Cortesías',                  'descripcion' => 'Producto de cortesía',               'precio' => 0.00,  'categoria_id' => 7, 'estado' => 'A'],
            ['nombre' => 'Inca Kola',                  'descripcion' => 'Inca Kola 500ml',                    'precio' => 3.50,  'categoria_id' => 7, 'estado' => 'A'],
            ['nombre' => 'Tapers',                     'descripcion' => 'Tapers para llevar',                 'precio' => 1.00,  'categoria_id' => 7, 'estado' => 'A'],
            ['nombre' => 'Velas',                      'descripcion' => 'Velas decorativas',                  'precio' => 1.00,  'categoria_id' => 7, 'estado' => 'A'],
            ['nombre' => 'Cajita decorativa',          'descripcion' => '',                                   'precio' => 3.00,  'categoria_id' => 7, 'estado' => 'A'],
        ];
        foreach ($productos as $p) {
            Producto::create($p);
        }

        $this->call(StockSeeder::class);
    }
}
