<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactoController extends Controller
{
    // Mostrar todos los contactos
    public function index()
    {
        $contactos = Contacto::all();
        return view('contactos.index');
    }

    // Almacenar un nuevo contacto
    public function store(Request $request) {    
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'telefono' => 'required|numeric|digits_between:8,15',
                'fecha_nacimiento' => 'required|date'
            ]);

            $contacto = Contacto::create($validated);
            return response()->json(['success' => true, 'contacto' => $contacto]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error del servidor: '.$e->getMessage()
            ], 500);
        }
    }

    // Mostrar un contacto específico
    public function show($id)
    {
        $contacto = Contacto::findOrFail($id);
        return response()->json($contacto);
    }

    // Actualizar un contacto
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|integer',
            'fecha_nacimiento' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contacto = Contacto::findOrFail($id);
        $contacto->update($request->all());
        return response()->json($contacto);
    }

    // Eliminar un contacto
    public function destroy($id)
    {
        $contacto = Contacto::findOrFail($id);
        $contacto->delete();
        return response()->json(null, 204);
    }
}
