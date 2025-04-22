<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
* @OA\Info(
*             title="API Restaurante CRUD",
*             version="1.0",
*             description="API para la gestión de Restaurantes: Comensales, Mesas y Reservas",
* )
*
* @OA\Server(url="http://localhost:8000")
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
