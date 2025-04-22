<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants;
use App\Http\Responses\ApiResponse;

class ReservaController extends Controller
{
    /**
     * Lista todas las reservas
     *
     * @OA\Get (
     *     path="/api/Reserva/listReserva",
     *     tags={"Reserva"},
     *     summary="Listar reservas",
     *     description="Retorna una lista de reservas incluyendo información del comensal y de la mesa asociada.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reservas",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="comensal_id", type="integer", example=2),
     *                     @OA\Property(property="nombre", type="string", example="JOSE ZAFRA"),
     *                     @OA\Property(property="mesa_id", type="integer", example=3),
     *                     @OA\Property(property="numero_mesa", type="string", example="10"),
     *                     @OA\Property(property="capacidad", type="integer", example=4),
     *                     @OA\Property(property="fecha", type="string", format="date", example="2024-04-21"),
     *                     @OA\Property(property="hora", type="string", example="19:30"),
     *                     @OA\Property(property="numero_de_personas", type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="error_message", type="string", example="Detalle del error"))
     *         )
     *     )
     * )
     */
    public function list()
    {
        //Se ordenon del ultimo registro al primero
        //Cargan las relaciones comensal y mesa
        $reserva = \App\Models\Reserva::with('comensal', 'mesa')
            ->orderBy('id', 'desc')
            ->get();

        //Casteo de datos
        $data = [];
        foreach ($reserva as $value) {
            $data[] = [
                'id'=> $value->id,
                'comensal_id' => $value->comensal_id,
                'nombre' => $value->comensal ? $value->comensal->nombre : null,
                'mesa_id' => $value->mesa_id,
                'numero_mesa' => $value->mesa ? $value->mesa->numero_mesa : null,
                'capacidad' => $value->mesa ? $value->mesa->capacidad : null,
                'fecha' => $value->fecha,
                'hora' => $value->hora,
                'numero_de_personas' => $value->numero_de_personas,
            ];
        }

        if($reserva->isEmpty()){
            return ApiResponse::warning(Constants::NO_HAY_DATOS, 200, $data);
        } else{
            return ApiResponse::success(Constants::OPERACION_EXITOSA, 200, $data);
        }
    }

    /**
     * Crear, editar o eliminar una reserva
     * @OA\Post(
     *     path="/api/Reserva/crudReserva",
     *     tags={"Reserva"},
     *     summary="Crear, editar o eliminar una reserva",
     *     description="Dependiendo del valor del parámetro 'action', se puede crear (c), actualizar (u) o eliminar (d) una reserva.
     *     Se valida que el número de personas no supere la capacidad de la mesa.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"action"},
     *             @OA\Property(property="action", type="string", example="c", description="Acción a realizar: 'c' para crear, 'u' para actualizar, 'd' para eliminar"),
     *             @OA\Property(property="id", type="integer", example=1, description="ID de la reserva (requerido para 'u' y 'd')"),
     *             @OA\Property(property="comensal_id", type="integer", example=1, description="ID del comensal (requerido para 'c' y 'u')"),
     *             @OA\Property(property="mesa_id", type="integer", example=1, description="ID de la mesa (requerido para 'c' y 'u')"),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-06-06", description="Fecha de la reserva (requerido para 'c' y 'u')"),
     *             @OA\Property(property="hora", type="string", example="18:00", description="Hora de la reserva (requerido para 'c' y 'u')"),
     *             @OA\Property(property="numero_de_personas", type="integer", example=2, description="Número de personas (requerido para 'c' y 'u')")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Operación exitosa"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="reserva", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="comensal_id", type="integer", example=1),
     *                     @OA\Property(property="mesa_id", type="integer", example=1),
     *                     @OA\Property(property="fecha", type="string", example="2025-06-06"),
     *                     @OA\Property(property="hora", type="string", example="18:00"),
     *                     @OA\Property(property="numero_de_personas", type="integer", example=2),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-21T14:22:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-21T14:22:00.000000Z")
     *                 ),
     *                 @OA\Property(property="status", type="integer", example=0),
     *                 @OA\Property(property="mesa_capacidad", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="error_message", type="string", example="Detalle del error")
     *             )
     *         )
     *     )
     * )
     */
    public function crud(Request $request)
    {
        try {
            $id = $request->id;
            $comensal_id = $request->comensal_id;
            $mesa_id = $request->mesa_id;
            $fecha = $request->fecha;
            $hora = $request->hora;
            $numero_de_personas = $request->numero_de_personas;

            $status = null;
            $reserva = null;
            $mesa_capacidad = null;

            // Obtener la capacidad de la mesa
            $mesa = \App\Models\Mesa::find($mesa_id);
            if($mesa) {
                $mesa_capacidad = $mesa->capacidad;
            }

             //Crear
            if ($request->action == 'c') {
                //Si el número de personas es mayor a la capacidad de la mesa, se retorna status 1 y no se crea
                if ($mesa_capacidad < $numero_de_personas) {
                    $status = 1;
                } else {
                    \DB::beginTransaction();
                        $reserva = \App\Models\Reserva::create([
                            'comensal_id' => $comensal_id,
                            'mesa_id' => $mesa_id,
                            'fecha' => $fecha,
                            'hora' => $hora,
                            'numero_de_personas' => $numero_de_personas,
                        ]);
                    \DB::commit();
                    $status = 0;
                }
            }
            //Editar
            elseif ($request->action == 'u') {
                //Si el número de personas es mayor a la capacidad de la mesa, se retorna status 1 y no se edita
                if ($mesa_capacidad < $numero_de_personas) {
                    $status = 1;
                } else {
                    \DB::beginTransaction();
                        $reserva = \App\Models\Reserva::find($id);
                        $reserva->comensal_id = $comensal_id;
                        $reserva->mesa_id =  $mesa_id;
                        $reserva->fecha = $fecha;
                        $reserva->hora = $hora;
                        $reserva->numero_de_personas = $numero_de_personas;
                        $reserva->save();
                    \DB::commit();
                    $status = 0;
                }
            }
            //Eliminar
            else {
                \DB::beginTransaction();
                    $reserva = \App\Models\Reserva::where('id', $id)->delete();
                    $status = 0;
                \DB::commit();
            }
            $responseData = [
                'reserva' => $reserva,
                'status' => $status,
                'mesa_capacidad' => $mesa_capacidad
            ];
            if($status == 1){
                return ApiResponse::warning(Constants::VALIDACION_NO_CUMPLIDA, 200, $responseData);
            } else {
                return ApiResponse::success(Constants::OPERACION_EXITOSA, 200, $responseData);
            }
        } catch (\Exception $e) {
            return ApiResponse::error(Constants::ERROR_500, 500, ['error_message' => $e->getMessage()]);
        }
    }
}