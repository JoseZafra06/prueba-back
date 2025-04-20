<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants;
use App\Http\Responses\ApiResponse;

class ComensalController extends Controller
{
    //Listado
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

    //Crear, Editar, Eliminar
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
