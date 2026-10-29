<?php

namespace Tests\Feature;

use App\Models\Personas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonasCrudTest extends TestCase
{
    use RefreshDatabase;

    private array $datosValidos = [
        'nombre'           => 'Juan',
        'paterno'          => 'Pérez',
        'materno'          => 'García',
        'fecha_nacimiento' => '1995-06-15',
    ];

    // --- Index ---

    public function test_index_muestra_listado_de_personas(): void
    {
        Personas::create($this->datosValidos);

        $this->get(route('personas.index'))
            ->assertStatus(200)
            ->assertSee('Juan');
    }

    // --- Create ---

    public function test_create_muestra_formulario(): void
    {
        $this->get(route('personas.create'))
            ->assertStatus(200)
            ->assertSee('Agregar');
    }

    // --- Store ---

    public function test_store_guarda_persona_y_redirige(): void
    {
        $this->post(route('personas.store'), $this->datosValidos)
            ->assertRedirect(route('personas.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('personas', ['nombre' => 'Juan', 'paterno' => 'Pérez']);
    }

    public function test_store_falla_si_faltan_campos_requeridos(): void
    {
        $this->post(route('personas.store'), [])
            ->assertSessionHasErrors(['nombre', 'paterno', 'materno', 'fecha_nacimiento']);
    }

    public function test_store_falla_si_nombre_supera_100_caracteres(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'nombre' => str_repeat('a', 101),
        ]))->assertSessionHasErrors(['nombre']);
    }

    public function test_store_falla_si_fecha_es_futura(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'fecha_nacimiento' => '2099-01-01',
        ]))->assertSessionHasErrors(['fecha_nacimiento']);
    }

    public function test_store_falla_si_fecha_no_es_valida(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'fecha_nacimiento' => 'no-es-fecha',
        ]))->assertSessionHasErrors(['fecha_nacimiento']);
    }

    // --- Edit ---

    public function test_edit_muestra_formulario_con_datos_de_persona(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->get(route('personas.edit', $persona->id))
            ->assertStatus(200)
            ->assertSee('Juan')
            ->assertSee('Pérez');
    }

    public function test_edit_retorna_404_si_persona_no_existe(): void
    {
        $this->get(route('personas.edit', 9999))
            ->assertStatus(404);
    }

    public function test_update_retorna_404_si_persona_no_existe(): void
    {
        $this->put(route('personas.update', 9999), $this->datosValidos)
            ->assertStatus(404);
    }

    // --- Update ---

    public function test_update_modifica_persona_y_redirige(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->put(route('personas.update', $persona->id), array_merge($this->datosValidos, [
            'nombre' => 'Carlos',
        ]))->assertRedirect(route('personas.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('personas', ['id' => $persona->id, 'nombre' => 'Carlos']);
    }

    public function test_update_falla_si_faltan_campos_requeridos(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->put(route('personas.update', $persona->id), [])
            ->assertSessionHasErrors(['nombre', 'paterno', 'materno', 'fecha_nacimiento']);
    }

    public function test_update_falla_si_fecha_es_futura(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->put(route('personas.update', $persona->id), array_merge($this->datosValidos, [
            'fecha_nacimiento' => '2099-01-01',
        ]))->assertSessionHasErrors(['fecha_nacimiento']);
    }

    // --- Destroy (soft delete) ---

    public function test_destroy_mueve_persona_a_papelera(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->delete(route('personas.destroy', $persona->id))
            ->assertRedirect(route('personas.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('personas', ['id' => $persona->id]);
    }

    public function test_index_no_muestra_personas_eliminadas(): void
    {
        $persona = Personas::create($this->datosValidos);
        $persona->delete();

        $this->get(route('personas.index'))
            ->assertStatus(200)
            ->assertDontSee($persona->nombre);
    }

    // --- Trash ---

    public function test_trash_muestra_personas_eliminadas(): void
    {
        $persona = Personas::create($this->datosValidos);
        $persona->delete();

        $this->get(route('personas.trash'))
            ->assertStatus(200)
            ->assertSee($persona->nombre);
    }

    // --- Restore ---

    public function test_restore_recupera_persona_eliminada(): void
    {
        $persona = Personas::create($this->datosValidos);
        $persona->delete();

        $this->put(route('personas.restore', $persona->id))
            ->assertRedirect(route('personas.trash'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('personas', ['id' => $persona->id, 'deleted_at' => null]);
    }

    // --- Force Delete ---

    public function test_force_delete_elimina_permanentemente(): void
    {
        $persona = Personas::create($this->datosValidos);
        $persona->delete();

        $this->delete(route('personas.forceDelete', $persona->id))
            ->assertRedirect(route('personas.trash'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('personas', ['id' => $persona->id]);
    }

    public function test_destroy_retorna_404_si_persona_no_existe(): void
    {
        $this->delete(route('personas.destroy', 9999))
            ->assertStatus(404);
    }

    // --- Home ---

    public function test_home_muestra_totales_en_la_dashboard(): void
    {
        Personas::create($this->datosValidos);
        Personas::factory()->create();

        $this->get(route('home.index'))
            ->assertOk()
            ->assertViewHas('totalPersonas', 2)
            ->assertViewHas('enPapelera', 0);
    }

    public function test_home_excluye_papelera_del_total_activo(): void
    {
        Personas::create($this->datosValidos);
        Personas::factory()->create();

        $deleted = Personas::factory()->create();
        $deleted->delete();

        $this->get(route('home.index'))
            ->assertOk()
            ->assertViewHas('totalPersonas', 2)
            ->assertViewHas('enPapelera', 1);
    }

    // --- Trash (404s) ---

    public function test_restore_retorna_404_si_persona_no_existe(): void
    {
        $this->put(route('personas.restore', 9999))
            ->assertStatus(404);
    }

    public function test_force_delete_no_elimina_persona_activa(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->delete(route('personas.forceDelete', $persona->id))->assertNotFound();

        $this->assertDatabaseHas('personas', ['id' => $persona->id, 'deleted_at' => null]);
    }

    public function test_store_rechaza_fecha_con_formato_distinto_de_iso(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'fecha_nacimiento' => '15/06/1995',
        ]))->assertSessionHasErrors(['fecha_nacimiento']);
    }

    public function test_force_delete_retorna_404_si_persona_no_existe(): void
    {
        $this->delete(route('personas.forceDelete', 9999))
            ->assertStatus(404);
    }

    // --- Validación de límites ---

    public function test_store_acepta_nombre_de_exactamente_100_caracteres(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'nombre' => str_repeat('b', 100),
        ]))->assertRedirect(route('personas.index'));

        $this->assertDatabaseHas('personas', ['nombre' => str_repeat('b', 100)]);
    }

    public function test_store_falla_si_paterno_supera_100_caracteres(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'paterno' => str_repeat('a', 101),
        ]))->assertSessionHasErrors(['paterno']);
    }

    public function test_store_falla_si_materno_supera_100_caracteres(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'materno' => str_repeat('a', 101),
        ]))->assertSessionHasErrors(['materno']);
    }

    // --- Fecha límite (app-clock, no DB clock) ---

    public function test_store_rechaza_fecha_de_hoy_exactamente(): void
    {
        $hoy = now()->format('Y-m-d');

        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'fecha_nacimiento' => $hoy,
        ]))->assertSessionHasErrors(['fecha_nacimiento']);
    }

    public function test_store_acepta_fecha_de_ayer(): void
    {
        $ayer = now()->subDay()->format('Y-m-d');

        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'fecha_nacimiento' => $ayer,
        ]))->assertRedirect(route('personas.index'));

        $this->assertDatabaseHas('personas', ['fecha_nacimiento' => $ayer]);
    }

    // --- Strings vacíos / solo espacios ---

    public function test_store_falla_si_nombre_contiene_solo_espacios(): void
    {
        $this->post(route('personas.store'), array_merge($this->datosValidos, [
            'nombre' => '   ',
        ]))->assertSessionHasErrors(['nombre']);
    }

    public function test_update_rechaza_nombre_de_solo_espacios(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->put(route('personas.update', $persona->id), array_merge($this->datosValidos, [
            'nombre' => '   ',
        ]))->assertSessionHasErrors(['nombre']);
    }

    // --- Update: reglas de límite y formato ---

    public function test_update_falla_si_paterno_supera_100_caracteres(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->put(route('personas.update', $persona->id), array_merge($this->datosValidos, [
            'paterno' => str_repeat('x', 101),
        ]))->assertSessionHasErrors(['paterno']);
    }

    public function test_update_rechaza_fecha_no_valida(): void
    {
        $persona = Personas::create($this->datosValidos);

        $this->put(route('personas.update', $persona->id), array_merge($this->datosValidos, [
            'fecha_nacimiento' => 'no-es-fecha',
        ]))->assertSessionHasErrors(['fecha_nacimiento']);
    }

    // --- Índice y papelera: vacío ---

    public function test_index_se_muestra_sin_registros(): void
    {
        $this->get(route('personas.index'))
            ->assertOk();
    }

    public function test_trash_se_muestra_vacio(): void
    {
        $this->get(route('personas.trash'))
            ->assertOk();
    }
}
