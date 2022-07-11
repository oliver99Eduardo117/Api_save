<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Solicitud;
use App\Models\Token;
use App\Models\Token_accesos;

trait ApiResponser{

   
    protected function tokenResponse($name, $ews_token)
    {
        return Token::where('descripcion', $name)->where('token', $ews_token)->get();
    }

    protected function saveTokenAccess($id_token, $mensaje, $codigo)
    {
        $token_access = new Token_accesos();
        $token_access->id_token = $id_token;
        $token_access->fecha = Carbon::now(-5)->toDateString();
        $token_access->hora = Carbon::now(-5)->toTimeString();
        //$token_access->ip = null;
        //$token_access->dato_clave = null;
        $token_access->mensaje = $mensaje;
        $token_access->codigo = $codigo;
        $token_access->save();
    }

    protected function errorResponse($message, $code, $id_token)
    {
        $this->saveTokenAccess( $id_token, $message, $code);
        return response()->json(['wsp_mensaje' => $message], $code);
    }

    protected function jsonResponse($request, $ApiDoc, $solicitud)
    {

        // Estructura del JSON
        $json =[
            "wsp_mensaje"  => "Ciudadano encontrado",
            "wsp_no_solicitud"  => $solicitud->no_solicitud,
            "wsp_no_solicitud_api"  => $solicitud->no_solicitud_api,
            "wsp_nivel"  => 2,
            "wsp_datos"  => [
                [
                    ['Ciudadano Encontrado'],
                    [
                        'Nombre',
                        $request->ews_nombre_sw
                    ],
                    [
                        'Apellido Paterno',
                        $request->ews_apellido_paterno_sw
                    ],
                    [
                        'Apellido Materno',
                        $request->ews_apellido_materno_sw
                    ],
                    [
                        'Curp',
                        $request->ews_curp_sw
                    ],
                    [
                        'Numero de licencia',
                        $request->ews_no_licencia
                    ],
                    [
                        'Tipo Licencia',
                        $request->ews_tipo_licencia
                    ],
                    [
                        'Sexo',
                        $request->ews_sexo_sw
                    ],
                    [
                        'Fecha Nacimiento',
                        $request->ews_fecha_nacimiento_sw
                    ],
                    [
                        'Tipo Sanguineo',
                        $request->ews_tipo_sanguineo
                    ],
                    [
                        'Codigo Postal',
                        $request->ews_codigo_postal_sw
                    ]
                ],
            ]
        ];

        return response()->json($json,200,
            [
                'Content-Type' => 'application/json',
                'Charset' => 'utf-8'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT
        );
    }

   
}
