<?php

namespace Tests\Feature;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\Marca;
use App\Models\User;
use App\Models\Vendedor;
use App\Models\Ventas;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ConcesionariaCrudTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Prueba el listado, búsqueda y ordenamiento de Vendedores.
     */
    public function test_vendedores_index_and_search_and_sort()
    {
        $leg1 = 'LEG-' . rand(1000, 9999);
        $leg2 = 'LEG-' . rand(1000, 9999);

        $v1 = Vendedor::create([
            'nombre'        => 'Juan',
            'apellido'      => 'Pérez',
            'legajo'        => $leg1,
            'comision'      => 4.5,
            'fecha_ingreso' => '2023-01-10',
            'activo'        => true,
        ]);

        $v2 = Vendedor::create([
            'nombre'        => 'Ana',
            'apellido'      => 'Gómez',
            'legajo'        => $leg2,
            'comision'      => 2.0,
            'fecha_ingreso' => '2024-05-15',
            'activo'        => true,
        ]);

        // Listar
        $response = $this->get(route('vendedores.index'));
        $response->assertStatus(200);
        $response->assertSee('Juan Pérez');
        $response->assertSee('Ana Gómez');

        // Búsqueda por legajo único
        $searchResponse = $this->get(route('vendedores.index', ['search' => $leg1]));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Juan Pérez');
        $searchResponse->assertDontSee('Ana Gómez');

        // Ordenamiento
        $sortResponse = $this->get(route('vendedores.index', ['sort' => 'comision_desc']));
        $sortResponse->assertStatus(200);
    }

    /**
     * Prueba la creación de un Vendedor con validación de FormRequest.
     */
    public function test_vendedor_create_and_validation()
    {
        // Fails validation if required fields are missing
        $invalidResponse = $this->post(route('vendedores.store'), []);
        $invalidResponse->assertSessionHasErrors(['nombre', 'apellido', 'legajo', 'comision', 'fecha_ingreso']);

        // Success when valid data is provided
        $leg = 'LEG-' . rand(10000, 99999);
        $validData = [
            'nombre'        => 'Marcos',
            'apellido'      => 'Test',
            'legajo'        => $leg,
            'comision'      => 5.0,
            'fecha_ingreso' => '2024-01-01',
            'activo'        => 1,
        ];

        $response = $this->post(route('vendedores.store'), $validData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('vendedores.index'));
        $this->assertDatabaseHas('vendedor', ['legajo' => $leg]);
    }

    /**
     * Prueba la edición y actualización de un Vendedor.
     */
    public function test_vendedor_update()
    {
        $leg = 'LEG-' . rand(10000, 99999);
        $vendedor = Vendedor::create([
            'nombre'        => 'Carlos',
            'apellido'      => 'Original',
            'legajo'        => $leg,
            'comision'      => 3.0,
            'fecha_ingreso' => '2023-01-01',
            'activo'        => true,
        ]);

        $updateData = [
            'nombre'        => 'Carlos',
            'apellido'      => 'Modificado',
            'legajo'        => $leg,
            'comision'      => 4.0,
            'fecha_ingreso' => '2023-01-01',
            'activo'        => 1,
        ];

        $response = $this->put(route('vendedores.update', $vendedor->id), $updateData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('vendedores.index'));
        $this->assertDatabaseHas('vendedor', ['id' => $vendedor->id, 'apellido' => 'Modificado', 'comision' => 4.0]);
    }

    /**
     * Prueba la eliminación de un Vendedor sin ventas.
     */
    public function test_vendedor_destroy()
    {
        $leg = 'LEG-' . rand(10000, 99999);
        $vendedor = Vendedor::create([
            'nombre'        => 'Eliminar',
            'apellido'      => 'Vendedor',
            'legajo'        => $leg,
            'comision'      => 3.0,
            'fecha_ingreso' => '2023-01-01',
            'activo'        => true,
        ]);

        $response = $this->delete(route('vendedores.destroy', $vendedor->id));
        $response->assertRedirect(route('vendedores.index'));
        $this->assertDatabaseMissing('vendedor', ['id' => $vendedor->id]);
    }

    /**
     * Prueba el CRUD de Ventas y el FormRequest de Venta.
     */
    public function test_ventas_crud_complete_flow()
    {
        $marca = Marca::firstOrCreate(['nombre' => 'TestMarca']);

        $auto = Auto::create([
            'id_marca'    => $marca->id,
            'modelo'      => 'Hilux Test CRUD ' . rand(100, 999),
            'anio'        => 2023,
            'precio'      => 25000000,
            'kilometraje' => 10000,
            'tipo'        => 'usado',
            'combustible' => 'diesel',
            'estado'      => 'disponible',
        ]);

        $leg = 'LEG-' . rand(10000, 99999);
        $vendedor = Vendedor::create([
            'nombre'        => 'Vendedor',
            'apellido'      => 'Ventas',
            'legajo'        => $leg,
            'comision'      => 3.5,
            'fecha_ingreso' => '2024-01-01',
            'activo'        => true,
        ]);

        // 1. Validar fallo con campos vacíos
        $invalid = $this->post(route('ventas.store'), []);
        $invalid->assertSessionHasErrors(['id_auto', 'cliente_tipo', 'fecha_venta', 'precio_final', 'metodo_pago']);

        // 2. Registrar venta exitosa con cliente nuevo
        $ventaData = [
            'id_auto'          => $auto->id,
            'id_vendedor'      => $vendedor->id,
            'cliente_tipo'     => 'nuevo',
            'cliente_nombre'   => 'Comprador',
            'cliente_apellido' => 'Prueba',
            'cliente_dni'      => '40111222',
            'fecha_venta'      => '2026-11-16',
            'precio_final'     => 24500000,
            'metodo_pago'      => 'transferencia',
        ];

        $storeResponse = $this->post(route('ventas.store'), $ventaData);
        $storeResponse->assertSessionHasNoErrors();

        // Verificar que el auto pasó a estado "vendido"
        $auto->refresh();
        $this->assertEquals('vendido', $auto->estado);

        $venta = Ventas::where('id_auto', $auto->id)->first();
        $this->assertNotNull($venta);
        $storeResponse->assertRedirect(route('ventas.show', $venta->id));

        // 3. Modificar venta
        $updateData = [
            'id_vendedor'  => $vendedor->id,
            'id_cliente'   => $venta->id_cliente,
            'fecha_venta'  => '2026-11-16',
            'precio_final' => 24000000,
            'metodo_pago'  => 'efectivo',
        ];

        $updateResponse = $this->put(route('ventas.update', $venta->id), $updateData);
        $updateResponse->assertSessionHasNoErrors();
        $updateResponse->assertRedirect(route('ventas.show', $venta->id));
        $this->assertDatabaseHas('ventas', ['id' => $venta->id, 'precio_final' => 24000000, 'metodo_pago' => 'efectivo']);

        // 4. Eliminar venta y verificar que el auto vuelve a disponible
        $deleteResponse = $this->delete(route('ventas.destroy', $venta->id));
        $deleteResponse->assertRedirect(route('ventas.index'));
        $this->assertDatabaseMissing('ventas', ['id' => $venta->id]);

        $auto->refresh();
        $this->assertEquals('disponible', $auto->estado);
    }

    /**
     * Prueba el buscador y ordenamiento del listado de Ventas.
     */
    public function test_ventas_search_and_sort()
    {
        $response = $this->get(route('ventas.index'));
        $response->assertStatus(200);

        // Búsqueda por método de pago
        $searchResponse = $this->get(route('ventas.index', ['metodo_pago' => 'transferencia']));
        $searchResponse->assertStatus(200);

        // Ordenamiento por precio descendente y ascendente
        $sortDescResponse = $this->get(route('ventas.index', ['sort' => 'precio_desc']));
        $sortDescResponse->assertStatus(200);

        $sortAscResponse = $this->get(route('ventas.index', ['sort' => 'precio_asc']));
        $sortAscResponse->assertStatus(200);

        // Ordenamiento por fecha ascendente
        $sortFechaResponse = $this->get(route('ventas.index', ['sort' => 'fecha_asc']));
        $sortFechaResponse->assertStatus(200);
    }

    /**
     * Prueba el CRUD y la validación de FormRequest para Auto.
     */
    public function test_auto_crud_and_validation()
    {
        $marca = Marca::firstOrCreate(['nombre' => 'Ford']);

        // 1. Error con campos vacíos
        $invalid = $this->post(route('autos.store'), []);
        $invalid->assertSessionHasErrors(['id_marca', 'modelo', 'anio', 'precio', 'kilometraje', 'tipo', 'combustible']);

        // 2. Guardar auto válido
        $modelName = 'Ranger XLS ' . rand(1000, 9999);
        $data = [
            'id_marca'    => $marca->id,
            'modelo'      => $modelName,
            'anio'        => 2022,
            'precio'      => 32000000,
            'kilometraje' => 45000,
            'tipo'        => 'usado',
            'combustible' => 'diesel',
            'descripcion' => 'Impecable unidad para trabajo o paseo.',
        ];

        $response = $this->post(route('autos.store'), $data);
        $response->assertSessionHasNoErrors();
        $auto = Auto::where('modelo', $modelName)->first();
        $this->assertNotNull($auto);
        $response->assertRedirect(route('autos.show', $auto->id));

        // 3. Modificar auto
        $updateData = array_merge($data, [
            'precio'      => 31000000,
            'kilometraje' => 46000,
            'estado'      => 'disponible',
        ]);
        $updateResponse = $this->put(route('autos.update', $auto->id), $updateData);
        $updateResponse->assertSessionHasNoErrors();
        $updateResponse->assertRedirect(route('autos.show', $auto->id));
        $this->assertDatabaseHas('auto', ['id' => $auto->id, 'precio' => 31000000]);

        // 4. Eliminar auto
        $deleteResponse = $this->delete(route('autos.destroy', $auto->id));
        $deleteResponse->assertRedirect(route('autos.index'));
        $this->assertDatabaseMissing('auto', ['id' => $auto->id]);
    }
}
