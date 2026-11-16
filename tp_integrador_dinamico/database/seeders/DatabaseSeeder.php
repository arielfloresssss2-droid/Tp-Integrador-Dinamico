<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\Marca;
use App\Models\User;
use App\Models\Vendedor;
use App\Models\Ventas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $marcasData = [
            ['nombre' => 'Toyota'],
            ['nombre' => 'Ford'],
            ['nombre' => 'Volkswagen'],
            ['nombre' => 'Chevrolet'],
            ['nombre' => 'RAM'],
            ['nombre' => 'Nissan'],
            ['nombre' => 'Fiat'],
            ['nombre' => 'Renault'],
            ['nombre' => 'Peugeot'],
            ['nombre' => 'Jeep'],
            ['nombre' => 'Honda'],
        ];

        $marcas = [];
        foreach ($marcasData as $m) {
            $marcas[$m['nombre']] = Marca::firstOrCreate(['nombre' => $m['nombre']]);
        }

        $vendedoresData = [
            [
                'nombre'        => 'Ariel',
                'apellido'      => 'Flores',
                'legajo'        => 'VEND-001',
                'comision'      => 3.5,
                'fecha_ingreso' => '2023-03-15',
                'activo'        => true,
            ],
            [
                'nombre'        => 'Lautaro',
                'apellido'      => 'Álvarez',
                'legajo'        => 'VEND-002',
                'comision'      => 4.0,
                'fecha_ingreso' => '2023-05-10',
                'activo'        => true,
            ],
            [
                'nombre'        => 'Carlos',
                'apellido'      => 'Gómez',
                'legajo'        => 'VEND-003',
                'comision'      => 3.0,
                'fecha_ingreso' => '2024-01-20',
                'activo'        => true,
            ],
            [
                'nombre'        => 'Mariana',
                'apellido'      => 'Benítez',
                'legajo'        => 'VEND-004',
                'comision'      => 2.5,
                'fecha_ingreso' => '2022-11-01',
                'activo'        => false,
            ],
        ];

        $vendedores = [];
        foreach ($vendedoresData as $vd) {
            $vendedores[$vd['legajo']] = Vendedor::firstOrCreate(
                ['legajo' => $vd['legajo']],
                $vd
            );
        }

        $userVendedor = User::firstOrCreate(
            ['email' => 'vendedor@pickupchacabuco.com'],
            [
                'name'     => 'Carlos Gómez (Vendedor)',
                'password' => Hash::make('password123'),
            ]
        );

        $comprador = User::firstOrCreate(
            ['email' => 'comprador@pickupchacabuco.com'],
            [
                'name'     => 'Martín Rodríguez',
                'password' => Hash::make('password123'),
            ]
        );

        $clientesData = [
            [
                'nombre'    => 'Martín',
                'apellido'  => 'Rodríguez',
                'dni'       => '36123456',
                'telefono'  => '2352456789',
                'direccion' => 'Av. Alsina 450, Chacabuco',
                'email'     => 'comprador@pickupchacabuco.com',
            ],
            [
                'nombre'    => 'Esteban',
                'apellido'  => 'Duhalde',
                'dni'       => '31876543',
                'telefono'  => '2352489900',
                'direccion' => 'San Martín 180, Chacabuco',
                'email'     => 'esteban.duhalde@gmail.com',
            ],
            [
                'nombre'    => 'Lucía',
                'apellido'  => 'Ferreira',
                'dni'       => '39443210',
                'telefono'  => '2352411223',
                'direccion' => 'Rivadavia 320, Chacabuco',
                'email'     => 'lucia.ferreira@hotmail.com',
            ],
        ];

        $clientes = [];
        foreach ($clientesData as $cd) {
            $clientes[] = Cliente::firstOrCreate(['email' => $cd['email']], $cd);
        }

        $autosData = [
            [
                'id_marca'    => $marcas['Toyota']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => 'Hilux 2.8 SRX 4x4 AT',
                'anio'        => 2023,
                'precio'      => 48500000,
                'kilometraje' => 18000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Impecable estado general. Único dueño, service oficial de los 10.000 km recién realizado en concesionario oficial. Tapizado de cuero, climatizador bi-zona, cámara 360°, control de crucero adaptativo. Lista para transferir.",
            ],
            [
                'id_marca'    => $marcas['Ford']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => 'Ranger Limited V6 3.0L 4WD',
                'anio'        => 2024,
                'precio'      => 62000000,
                'kilometraje' => 0,
                'tipo'        => 'nuevo',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "0 Km a patentar. Nueva generación Ford Ranger con motor V6 Turbo diésel y caja automática de 10 velocidades. Tablero 100% digital, pantalla vertical SYNC 4 de 12 pulgadas.",
            ],
            [
                'id_marca'    => $marcas['Volkswagen']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => 'Amarok V6 3.0 TDI Extreme 4Motion',
                'anio'        => 2022,
                'precio'      => 43800000,
                'kilometraje' => 38000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Excelente pick-up deportiva con 258 CV de potencia. Llantas de aleación de 20 pulgadas, barra deportiva Black, estribos planos, tapizado en cuero Nappa. Muy cuidada, uso en ruta.",
            ],
            [
                'id_marca'    => $marcas['Chevrolet']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => 'S10 High Country 2.8 TD 4x4',
                'anio'        => 2021,
                'precio'      => 36500000,
                'kilometraje' => 55000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Tope de gama High Country. Motor 2.8 Turbo con 200 CV y 500 Nm de torque. Wi-Fi nativo a bordo, sistema OnStar, frenado autónomo de emergencia.",
            ],
            [
                'id_marca'    => $marcas['RAM']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => '1500 Laramie V8 HEMI 5.7L',
                'anio'        => 2020,
                'precio'      => 58000000,
                'kilometraje' => 45000,
                'tipo'        => 'usado',
                'combustible' => 'nafta',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Potencia y confort sin rivales. Motor HEMI V8 con 395 CV, techo solar panorámico, audio premium Alpine, asientos ventilados y calefaccionados.",
            ],
            [
                'id_marca'    => $marcas['Toyota']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => 'Corolla Cross 1.8 SEG Híbrido',
                'anio'        => 2023,
                'precio'      => 39000000,
                'kilometraje' => 22000,
                'tipo'        => 'usado',
                'combustible' => 'hibrido',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "SUV híbrido con consumo súper reducido (más de 20 km por litro en ciudad). Garantía oficial de 8 años en batería y sistema híbrido.",
            ],
            [
                'id_marca'    => $marcas['Nissan']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => 'Frontier PRO-4X 2.3 Bi-Turbo 4x4',
                'anio'        => 2022,
                'precio'      => 41000000,
                'kilometraje' => 32000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'vendido',
                'imagen'      => null,
                'descripcion' => "Versión off-road PRO-4X con bloqueo de diferencial trasero, amortiguadores reforzados, detalles en rojo lava y cámara off-road 360.",
            ],
            [
                'id_marca'    => $marcas['Fiat']->id,
                'user_id'     => $userVendedor->id,
                'modelo'      => 'Toro Volcano 2.0 16v Multijet 4x4',
                'anio'        => 2022,
                'precio'      => 29500000,
                'kilometraje' => 41000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'vendido',
                'imagen'      => null,
                'descripcion' => "Caja automática de 9 marchas, tracción integral 4x4, tapizados en cuero, climatizador automático bizona y central multimedia vertical de 10 pulgadas.",
            ],
        ];

        $autos = [];
        foreach ($autosData as $data) {
            $autos[] = Auto::create($data);
        }

        Ventas::create([
            'user_id'      => $comprador->id,
            'id_cliente'   => $clientes[0]->id,
            'id_auto'      => $autos[6]->id,
            'id_vendedor'  => $vendedores['VEND-001']->id,
            'fecha_venta'  => now()->subDays(12)->toDateString(),
            'precio_final' => 41000000,
            'metodo_pago'  => 'transferencia',
        ]);

        Ventas::create([
            'user_id'      => null,
            'id_cliente'   => $clientes[1]->id,
            'id_auto'      => $autos[7]->id,
            'id_vendedor'  => $vendedores['VEND-002']->id,
            'fecha_venta'  => now()->subDays(4)->toDateString(),
            'precio_final' => 29500000,
            'metodo_pago'  => 'efectivo',
        ]);
    }
}
