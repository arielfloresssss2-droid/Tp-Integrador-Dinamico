<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\Marca;
use App\Models\User;
use App\Models\Ventas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial brands, demo users, and pickup trucks.
     *
     * @return void
     */
    public function run()
    {
        // 1. Marcas principales
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

        // 2. Usuarios demo
        $vendedor = User::firstOrCreate(
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

        // Cliente para compatibilidad de esquema
        $cliente = Cliente::firstOrCreate(
            ['email' => $comprador->email],
            [
                'nombre'    => 'Martín',
                'apellido'  => 'Rodríguez',
                'dni'       => '36123456',
                'telefono'  => 2352456789,
                'direccion' => 'Av. Alsina 450, Chacabuco',
            ]
        );

        // 3. Vehículos de muestra (Pick-Ups y Autos)
        $autosData = [
            [
                'id_marca'    => $marcas['Toyota']->id,
                'user_id'     => $vendedor->id,
                'modelo'      => 'Hilux 2.8 SRX 4x4 AT',
                'anio'        => 2023,
                'precio'      => 48500000,
                'kilometraje' => 18000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Impecable estado general. Único dueño, service oficial de los 10.000 km recién realizado en concesionario oficial. Tapizado de cuero, climatizador bi-zona, cámara 360°, control de crucero adaptativo. Lista para transferir con verificación policial y VTV vigente.",
            ],
            [
                'id_marca'    => $marcas['Ford']->id,
                'user_id'     => $vendedor->id,
                'modelo'      => 'Ranger Limited V6 3.0L 4WD',
                'anio'        => 2024,
                'precio'      => 62000000,
                'kilometraje' => 0,
                'tipo'        => 'nuevo',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "0 Km a patentar. Nueva generación Ford Ranger con motor V6 Turbo diésel y caja automática de 10 velocidades. Tablero 100% digital, pantalla vertical SYNC 4 de 12 pulgadas, paquete de asistencias a la conducción Ford Co-Pilot 360.",
            ],
            [
                'id_marca'    => $marcas['Volkswagen']->id,
                'user_id'     => $vendedor->id,
                'modelo'      => 'Amarok V6 3.0 TDI Extreme 4Motion',
                'anio'        => 2022,
                'precio'      => 43800000,
                'kilometraje' => 38000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Excelente pick-up deportiva con 258 CV de potencia. Llantas de aleación de 20 pulgadas, barra deportiva Black, estribos planos, tapizado en cuero Nappa. Muy cuidada, uso particular en ruta.",
            ],
            [
                'id_marca'    => $marcas['Chevrolet']->id,
                'user_id'     => $vendedor->id,
                'modelo'      => 'S10 High Country 2.8 TD 4x4',
                'anio'        => 2021,
                'precio'      => 36500000,
                'kilometraje' => 55000,
                'tipo'        => 'usado',
                'combustible' => 'diesel',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Tope de gama High Country. Motor 2.8 Turbo con 200 CV y 500 Nm de torque. Wi-Fi nativo a bordo, sistema OnStar, frenado autónomo de emergencia y alerta de colisión frontal.",
            ],
            [
                'id_marca'    => $marcas['RAM']->id,
                'user_id'     => $vendedor->id,
                'modelo'      => '1500 Laramie V8 HEMI 5.7L',
                'anio'        => 2020,
                'precio'      => 58000000,
                'kilometraje' => 45000,
                'tipo'        => 'usado',
                'combustible' => 'nafta',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "Potencia y confort sin rivales. Motor HEMI V8 con 395 CV, techo solar panorámico, audio premium Alpine, asientos ventilados y calefaccionados. Tratamiento cerámico recién aplicado.",
            ],
            [
                'id_marca'    => $marcas['Toyota']->id,
                'user_id'     => $vendedor->id,
                'modelo'      => 'Corolla Cross 1.8 SEG Híbrido',
                'anio'        => 2023,
                'precio'      => 39000000,
                'kilometraje' => 22000,
                'tipo'        => 'usado',
                'combustible' => 'hibrido',
                'estado'      => 'disponible',
                'imagen'      => null,
                'descripcion' => "SUV híbrido con consumo súper reducido (más de 20 km por litro en ciudad). Garantía oficial de 8 años en batería y sistema híbrido. Impecable por donde se lo mire.",
            ],
            [
                'id_marca'    => $marcas['Nissan']->id,
                'user_id'     => $vendedor->id,
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
        ];

        foreach ($autosData as $data) {
            $auto = Auto::create($data);

            // Si está vendido, creamos la venta de ejemplo con el comprador
            if ($auto->estado === 'vendido') {
                Ventas::create([
                    'user_id'      => $comprador->id,
                    'id_cliente'   => $cliente->id,
                    'id_auto'      => $auto->id,
                    'id_vendedor'  => null,
                    'fecha_venta'  => now()->subDays(5)->toDateString(),
                    'precio_final' => $auto->precio,
                    'metodo_pago'  => 'transferencia',
                ]);
            }
        }
    }
}
