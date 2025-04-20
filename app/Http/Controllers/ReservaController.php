<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants;
use App\Http\Responses\ApiResponse;

class ReservaController extends Controller
{
     //Listado
    public function list()
    {
        //Se ordenon del ultimo registro al primero
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

     //Crear, Editar, Eliminar
     //Se hizo validacion para que no se pueda crear o editar reserva con un numero de personas mayor a la capacidad de la mesa
    public function crud(Request $request)
    {
        try {
            $id = $request->id;
            $comensal_id = $request->comensal_id;
            $mesa_id = $request->mesa_id;
            $fecha = $request->fecha;
            $hora = $request->hora;
            $numero_de_personas = $request->numero_de_personas;

            // Obtener la capacidad de la mesa
            $status = null;
            $reserva = null;
            $mesa_capacidad = null;
            $mesa = \App\Models\Mesa::find($mesa_id);
            if($mesa) {
                $mesa_capacidad = $mesa->capacidad;
            }

             //Crear
            if ($request->action == 'c') {
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
                \DB::commit();
            }
            $responseData = [
                'reserva' => $reserva,
                'status' => $status,
                'mesa_capacidad' => $mesa_capacidad
            ];
             return ApiResponse::success(Constants::OPERACION_EXITOSA, 200, $responseData);
        } catch (\Exception $e) {
            return ApiResponse::error(Constants::ERROR_500, 500, ['error_message' => $e->getMessage()]);
        }
    }
}
