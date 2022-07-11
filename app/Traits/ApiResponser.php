<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\tokens;

trait ApiResponser{

   
    protected function tokenResponse($name, $ews_token)
    {
        var_dump($ews_token);
        return tokens::where('descripcion', $name)->where('token', $ews_token)->get();
    }

    protected function saveTokenAccess($id_token, $mensaje, $codigo)
    {
        $token_access = new tokens();
        $token_access->id_token = $id_token;
        $token_access->mensaje = $mensaje;
        $token_access->codigo = $codigo;
    }

    protected function errorResponse($message, $code, $id_token)
    {
        return response()->json(['wsp_mensaje' => $message], $code);
    }

 
}
