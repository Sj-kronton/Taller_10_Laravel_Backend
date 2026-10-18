<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\JsonResponse;

class MetricaController extends Controller
{
    // RF 3.1: total consolidado de eventos y monto global recaudado
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'total_eventos' => Evento::count(),
            // El modelo de datos del taller no incluye campos monetarios,
            // por eso el recaudo se reporta en 0.
            'monto_total_recaudado' => 0,
        ], 200);
    }
}