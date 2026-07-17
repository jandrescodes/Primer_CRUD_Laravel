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
}
