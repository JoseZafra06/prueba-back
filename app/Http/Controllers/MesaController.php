<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants;
use App\Http\Responses\ApiResponse;

class MesaController extends Controller
{
    /**
     * Lista todas las mesas activas
     *
     * @OA\Get (
     *     path="/api/Mesa/listMesa",
     *     tags={"Mesa"},
     *     summary="Listar mesas",
     *     description="Retorna una lista de mesas con estado activo (state = 1)",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de mesas",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="numero_mesa",
     *                         type="string",
     *                         example="M-01"
     *                     ),
     *                     @OA\Property(
     *                         property="capacidad",
     *                         type="integer",
     *                         example=4
     *                     ),
     *                     @OA\Property(
     *                         property="ubicacion",
     *                         type="string",
     *                         example="Zona A"
     *                     ),
     *                     @OA\Property(
     *                         property="state",
     *                         type="integer",
     *                         example=1
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function list()
    {
        //Se filtran por state 1 que son aquellos que no están eliminados
        //Se ordenon del ultimo registro al primero
        $mesa = \App\Models\Mesa::where('state', 1)
            ->orderBy('id', 'desc')
            ->get();

        if($mesa->isEmpty()){
            return ApiResponse::warning(Constants::NO_HAY_DATOS, 200, $mesa);
        } else{
            return ApiResponse::success(Constants::OPERACION_EXITOSA, 200, $mesa);
        }
    }

    /**
     * Crear, editar o eliminar una mesa
     * @OA\Post(
     *     path="/api/Mesa/crudMesa",
     *     tags={"Mesa"},
     *     summary="Crear, editar o eliminar una mesa",
     *     description="Dependiendo del valor del parámetro 'action', se puede crear (c), actualizar (u) o eliminar (d) una mesa.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"action"},
     *             @OA\Property(property="action", type="string", example="c", description="Acción a realizar: 'c' para crear, 'u' para actualizar, 'd' para eliminar"),
     *             @OA\Property(property="id", type="integer", example=1, description="ID de la mesa (requerido para 'u' y 'd')"),
     *             @OA\Property(property="numero_mesa", type="integer", example=10, description="Número de la mesa (requerido para 'c' y 'u')"),
     *             @OA\Property(property="capacidad", type="integer", example=5, description="Capacidad de la mesa (requerido para 'c' y 'u')"),
     *             @OA\Property(property="ubicacion", type="string", example="ZONA VIP", description="Ubicación de la mesa (opcional para 'c' y 'u')")
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
     *                 @OA\Property(property="mesa", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="numero_mesa", type="integer", example=10),
     *                     @OA\Property(property="capacidad", type="integer", example=5),
     *                     @OA\Property(property="ubicacion", type="string", example="ZONA VIP"),
     *                     @OA\Property(property="state", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-21T14:22:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-21T14:22:00.000000Z")
     *                 ),
     *                 @OA\Property(property="status", type="integer", example=0)
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
    public function crud(Request $request)
    {
        try {
            $id = $request->id;
            $numero_mesa = $request->numero_mesa;
            $capacidad = $request->capacidad;
            $ubicacion = trim($request->ubicacion) !== '' ? strtoupper($request->ubicacion) : null;

            $status = null;
            $mesa = null;

            //Crear
            if ($request->action == 'c') {
                //Valdacion en caso que ya exista el numero de mesa
                $exists = \App\Models\Mesa::where('numero_mesa', $numero_mesa)
                ->where('state', 1)
                ->exists();

                if ($exists) {
                    //Si existe el numero de mesa, se retorna status 1 y no se crea
                    $status = 1;
                } else {
                    \DB::beginTransaction();
                        $mesa = \App\Models\Mesa::create([
                            'numero_mesa'=> $numero_mesa,
                            'capacidad' => $capacidad,
                            'ubicacion' => $ubicacion,
                            'state' => 1,
                        ]);
                    \DB::commit();
                    $status = 0;
                }
            }
            //Editar
            elseif ($request->action == 'u') {
                //Valdacion en caso que ya exista el numero de mesa
                $exists = \App\Models\Mesa::where('numero_mesa', $numero_mesa)
                ->where('state', 1)
                ->where('id', '!=', $id)
                ->exists();

                if ($exists) {
                    //Si existe el numero de mesa, se retorna status 1 y no se edita
                    $status = 1;
                } else {
                    \DB::beginTransaction();
                        $mesa = \App\Models\Mesa::find($id);
                        $mesa->numero_mesa = $numero_mesa;
                        $mesa->capacidad =  $capacidad;
                        $mesa->ubicacion = $ubicacion;
                        $mesa->save();
                    \DB::commit();
                    $status = 0;
                }
            }
            //Eliminar
            else {
                \DB::beginTransaction();
                    $mesa = \App\Models\Mesa::find($id);
                    $mesa->state = 0;
                    $mesa->save();
                    $status = 0;
                \DB::commit();
            }
            $responseData = [
                'mesa' => $mesa,
                'status' => $status
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
