<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA; // <-- Uso de formato nativo de PHP 8.2

#[OA\Info(title: "API de Control de Asistencias - Grupo 7", version: "1.0.0", description: "CRUD de asistencias para el Logro B")]
#[OA\Server(url: "http://127.0.0.1:8000/api", description: "Servidor Local de API")]
class AsistenciaController extends Controller
{
    #[OA\Get(path: "/asistencias", summary: "Obtener todas las asistencias", tags: ["Asistencias"])]
    #[OA\Response(response: 200, description: "Lista de asistencias recuperada con éxito")]
    #[OA\Response(response: 500, description: "Error interno del servidor al obtener los datos")]
    public function getAllAsistencias()
    {
        $asistencias = Asistencia::all();
        return response()->json($asistencias);
    }

    #[OA\Get(path: "/asistencias/{id}", summary: "Buscar una asistencia por su ID", tags: ["Asistencias"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID de la asistencia", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Asistencia encontrada")]
    #[OA\Response(response: 404, description: "Asistencia no encontrada en la base de datos")]
    #[OA\Response(response: 500, description: "Error interno del servidor al procesar la búsqueda")]
    public function getAsistenciaById($id)
    {
        $asistencia = Asistencia::find($id);
        if ($asistencia) {
            return response()->json($asistencia);
        } else {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }
    }

    #[OA\Post(path: "/asistencias", summary: "Crear una nueva asistencia", tags: ["Asistencias"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["estudiante", "materia", "fecha", "estado"],
            properties: [
                new OA\Property(property: "estudiante", type: "string", example: "Juan Pérez"),
                new OA\Property(property: "materia", type: "string", example: "Programación Web"),
                new OA\Property(property: "fecha", type: "string", example: "2026-05-29"),
                new OA\Property(property: "estado", type: "string", example: "Presente"),
                new OA\Property(property: "observaciones", type: "string", example: "Ninguna")
            ]
        )
    )]
    #[OA\Response(response: 201, description: "Asistencia creada exitosamente")]
    #[OA\Response(response: 400, description: "Datos incompletos o fecha con formato no válido")]
    #[OA\Response(response: 500, description: "Error interno del servidor al intentar guardar el registro")]
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

    #[OA\Put(path: "/asistencias/{id}", summary: "Actualizar una asistencia existente", tags: ["Asistencias"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID de la asistencia", schema: new OA\Schema(type: "integer"))]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["estudiante", "materia", "fecha", "estado"],
            properties: [
                new OA\Property(property: "estudiante", type: "string", example: "Juan Pérez"),
                new OA\Property(property: "materia", type: "string", example: "Programación Web"),
                new OA\Property(property: "fecha", type: "string", example: "2026-05-29"),
                new OA\Property(property: "estado", type: "string", example: "Ausente"),
                new OA\Property(property: "observaciones", type: "string", example: "Justificado por salud")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Asistencia actualizada exitosamente")]
    #[OA\Response(response: 400, description: "Datos incompletos o fecha con formato no válido")]
    #[OA\Response(response: 404, description: "Asistencia no encontrada para actualizar")]
    #[OA\Response(response: 500, description: "Error interno del servidor al intentar modificar el registro")]
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

    #[OA\Delete(path: "/asistencias/{id}", summary: "Eliminar una asistencia por su ID", tags: ["Asistencias"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID de la asistencia a eliminar", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Asistencia eliminada exitosamente")]
    #[OA\Response(response: 404, description: "Asistencia no encontrada para eliminar")]
    #[OA\Response(response: 500, description: "Error interno del servidor al intentar borrar el registro")]
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