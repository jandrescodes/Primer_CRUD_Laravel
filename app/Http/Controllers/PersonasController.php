<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\Personas;

class PersonasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = Personas::all();
        return view('people.index', compact('datos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('people.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        //
        $personas = new Personas();
        $personas->paterno = $request->post('paterno');
        $personas->materno = $request->post('materno');
        $personas->nombre = $request->post('nombre');
        $personas->fecha_nacimiento = $request->post('fecha_nacimiento');

        $personas->save();

        return redirect()->route('personas.index')->with('success', '¡Agregado Exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Personas $personas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $persona = Personas::find($id);
        return view('people.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonaRequest $request, $id)
    {
        //
        $personas = Personas::find($id);
        $personas->paterno = $request->post('paterno');
        $personas->materno = $request->post('materno');
        $personas->nombre = $request->post('nombre');
        $personas->fecha_nacimiento = $request->post('fecha_nacimiento');

        $personas->save();

        return redirect()->route('personas.index')->with('success', '¡Registro Actualizado Exitosamente!');
    }

    public function destroy($id)
    {
        $personas = Personas::findOrFail($id);
        $personas->delete();
        return redirect()->route('personas.index')->with('success', '¡Registro movido a la papelera!');
    }

    public function trash()
    {
        $datos = Personas::onlyTrashed()->get();
        return view('people.trash', compact('datos'));
    }

    public function restore($id)
    {
        Personas::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('personas.trash')->with('success', '¡Registro restaurado exitosamente!');
    }

    public function forceDelete($id)
    {
        Personas::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('personas.trash')->with('success', '¡Registro eliminado permanentemente!');
    }
}
