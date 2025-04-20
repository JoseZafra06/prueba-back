<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants;
use App\Http\Responses\ApiResponse;

class MesaController extends Controller
{
    //Listado
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

    //Crear, Editar, Eliminar
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
                //Valdacion en caso que exista el numero de mesa
                $exists = \App\Models\Mesa::where('numero_mesa', $numero_mesa)
                ->where('state', 1)
                ->exists();

                if ($exists) {
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
                //Valdacion en caso que exista el numero de mesa
                $exists = \App\Models\Mesa::where('numero_mesa', $numero_mesa)
                ->where('state', 1)
                ->where('id', '!=', $id)
                ->exists();

                if ($exists) {
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
                \DB::commit();
            }
            $responseData = [
                'mesa' => $mesa,
                'status' => $status
            ];
            return ApiResponse::success(Constants::OPERACION_EXITOSA, 200, $responseData);
        } catch (\Exception $e) {
            return ApiResponse::error(Constants::ERROR_500, 500, ['error_message' => $e->getMessage()]);
        }
    }
}
