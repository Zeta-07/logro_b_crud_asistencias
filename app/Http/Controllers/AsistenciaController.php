<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{

    public function getAllAsistencias()
    {
        $asistencias = Asistencia::all();
        return response()->json($asistencias);
    }

    
    public function getAsistenciaById($id)
    {
        $asistencia = Asistencia::find($id);
        if ($asistencia) {
            return response()->json($asistencia);
        } else {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }
    }

    
    public function createAsistencia(Request $request)
    {
        // Validar la fecha
        if ($request->fecha) {
            $timestamp = strtotime($request->fecha);

            // Si strtotime devuelve false, significa que el texto NO es una fecha válida
            if ($timestamp === false) {
                return response()->json(['message' => 'Fecha no válida, debe ser en formato YYYY-MM-DD'], 400);
            }

            // Si es válida, la guardamos bien formateada (Año-Mes-Día)
            $request->fecha = date('Y-m-d', $timestamp);
        }
        if ($request->estudiante && $request->materia && $request->fecha && $request->estado) {
            $asistencia                = new Asistencia();
            $asistencia->estudiante    = $request->estudiante;
            $asistencia->materia       = $request->materia;
            $asistencia->fecha         = $request->fecha;
            $asistencia->estado        = $request->estado;
            $asistencia->observaciones = $request->observaciones;
            $asistencia->save();
            return response()->json(['message' => 'Asistencia creada exitosamente', 'asistencia' => $asistencia], 201);
        } else {
            return response()->json(['message' => 'Datos incompletos'], 400);
        }
    }

    
    public function updateAsistencia(Request $request, $id)
    {
        $asistencia = Asistencia::find($id);
        if (! $asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }
        if ($request->estudiante && $request->materia && $request->fecha && $request->estado) {
            $timestamp = strtotime($request->fecha);
            if ($timestamp === false) {
                return response()->json(['message' => 'Fecha no válida, debe ser en formato YYYY-MM-DD'], 400);
            }
            $fechaFormateada           = date('Y-m-d', $timestamp);
            $asistencia->estudiante    = $request->estudiante;
            $asistencia->materia       = $request->materia;
            $asistencia->fecha         = $fechaFormateada;
            $asistencia->estado        = $request->estado;
            $asistencia->observaciones = $request->observaciones;
            $asistencia->save();
            return response()->json(['message' => 'Asistencia actualizada exitosamente', 'asistencia' => $asistencia]);
        } else {
            return response()->json(['message' => 'Datos incompletos'], 400);
        }
    }

   
    public function deleteAsistencia($id)
    {
        $asistencia = Asistencia::find($id);
        if ($asistencia) {
            $asistencia->delete();
            return response()->json(['message' => 'Asistencia eliminada exitosamente']);
        } else {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }
    }
}