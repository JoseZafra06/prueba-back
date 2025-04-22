<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants;
use App\Http\Responses\ApiResponse;

class ComensalController extends Controller
{
    /**
     * Lista todos los comensales activos
     * @OA\Get (
     *     path="/api/Comensal/listComensal",
     *     tags={"Comensal"},
     *     summary="Listar comensales",
     *     description="Retorna una lista de comensales con estado activo (state = 1)",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de comensales",
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
     *                         property="nombre",
     *                         type="string",
     *                         example="Juan Pérez"
     *                     ),
     *                     @OA\Property(
     *                         property="correo",
     *                         type="string",
     *                         example="juan.perez@example.com"
     *                     ),
     *                     @OA\Property(
     *                         property="telefono",
     *                         type="string",
     *                         example="987654321"
     *                     ),
     *                     @OA\Property(
     *                         property="direccion",
     *                         type="string",
     *                         example="Av. Siempre Viva 123"
     *                     ),
     *                     @OA\Property(
     *                         property="state",
     *                         type="integer",
     *                         example=1
     *                     ),
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
        $comensal = \App\Models\Comensal::where('state', 1)
            ->orderBy('id', 'desc')
            ->get();

        if($comensal->isEmpty()){
            return ApiResponse::warning(Constants::NO_HAY_DATOS, 200, $comensal);
        } else{
            return ApiResponse::success(Constants::OPERACION_EXITOSA, 200, $comensal);
        }
    }

    /**
     * Crear, editar o eliminar un comensal
     * @OA\Post(
     *     path="/api/Comensal/crudComensal",
     *     tags={"Comensal"},
     *     summary="Crear, editar o eliminar un comensal",
     *     description="Dependiendo del valor del parámetro 'action', se puede crear (c), actualizar (u) o eliminar (d) un comensal.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"action"},
     *             @OA\Property(property="action", type="string", example="c", description="Acción a realizar: 'c' para crear, 'u' para actualizar, 'd' para eliminar"),
     *             @OA\Property(property="id", type="integer", example=1, description="ID del comensal (requerido para 'u' y 'd')"),
     *             @OA\Property(property="nombre", type="string", example="JOSE ZAFRA", description="Nombre del comensal (requerido para 'c' y 'u')"),
     *             @OA\Property(property="correo", type="string", example="josereynaldozv@gmail.com", description="Correo electrónico del comensal"),
     *             @OA\Property(property="telefono", type="string", example="934466163", description="Teléfono del comensal"),
     *             @OA\Property(property="direccion", type="string", example="AV. BALTA", description="Dirección del comensal")
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
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="JOSE ZAFRA"),
     *                 @OA\Property(property="correo", type="string", example="josereynaldozv@gmail.com"),
     *                 @OA\Property(property="telefono", type="string", example="934466163"),
     *                 @OA\Property(property="direccion", type="string", example="AV. BALTA"),
     *                 @OA\Property(property="state", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-21T14:22:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-21T14:22:00.000000Z")
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
            $nombre = strtoupper(trim($request->nombre));
            $correo = $request->correo;
            $telefono = $request->telefono ?? null;
            $direccion = trim($request->direccion) !== '' ? strtoupper($request->direccion) : null;

            //Crear
            if ($request->action == 'c') {
                \DB::beginTransaction();
                    $comensal = \App\Models\Comensal::create([
                        'nombre'=> $nombre,
                        'correo' => $correo,
                        'telefono' => $telefono,
                        'direccion' => $direccion,
                        'state' => 1,
                    ]);
                \DB::commit();
            }
             //Editar
            elseif ($request->action == 'u') {
                \DB::beginTransaction();
                    $comensal = \App\Models\Comensal::find($id);
                    $comensal->nombre = $nombre;
                    $comensal->correo =  $correo;
                    $comensal->telefono = $telefono;
                    $comensal->direccion = $direccion;
                    $comensal->save();
                \DB::commit();
            }
            //Eliminar
            else {
                \DB::beginTransaction();
                    $comensal = \App\Models\Comensal::find($id);
                    $comensal->state = 0;
                    $comensal->save();
                \DB::commit();
            }
            return ApiResponse::success(Constants::OPERACION_EXITOSA, 200, $comensal);
        } catch (\Exception $e) {
            return ApiResponse::error(Constants::ERROR_500, 500, ['error_message' => $e->getMessage()]);
        }
    }
}