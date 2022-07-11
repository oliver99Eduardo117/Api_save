<?php

namespace App\Http\Controllers;

use App\Models\ASIGNATURA_X_DOCENTE;
use App\Models\DOCENTE;
use App\Models\HORARIO_DISPONIBLE;
use App\Models\tokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class Api1Controller extends ApiController
{
    
    public function store(Request $request){
        $erroresValidacion = "";
        
     //dd($request['ews_token']);
    //Validacion del Token
    $validarTK = $this->tokenResponse('Acceso API 1 ', $request->ews_token);
    if($validarTK->count()==0){
        $mensaje = 'TOKEN Inválido o Inexistente';
        $this->saveTokenAccess(1, $mensaje, 403);
        return response()->json(['wsp_mensaje' => $mensaje], 403);
    } 
    
        
         //Validacion de los campos del Request usando reglas
         $validator = Validator::make($request->all(),[
            'ews_token' => 'required',
            'ews_correo_sw' => 'required',
            'ews_telefono_sw' => 'required',
            'ews_campus_sw' => 'required',
            'ews_id_docente_sw' => 'required',
            'ews_asignatura_sw' => 'required',
            'ews_descripcion_sw' => 'required',
            'ews_dia_sw' => 'required',
            'ews_hora_inicio_sw' => 'required',
            'ews_hora_fin_sw' => 'required',
            'ews_turno_sw' => 'required',
         ]);

         if ($validator->fails()) {
            $errors = $validator->errors();
            foreach($errors->all() as $message) {
                $erroresValidacion .= $message . ' ';
            }

            $mensaje = 'La solicitud no fue válida, porque: ' . $erroresValidacion;
            return $this->errorResponse($mensaje, 400, 1);
        }
            
        // Estructura Solicitud
        $docente = new DOCENTE();
        $docente->ID = $request->ews_id_docente_sw;
        $docente->CORREO = $request->ews_correo_sw;
        $docente->TELEFONO = $request->ews_telefono_sw;
        $docente->DESCRIPCION = $request->ews_descripcion_sw;   

        $asig_docen = new ASIGNATURA_X_DOCENTE();
        $asig_docen->ASIGNATURA = $request->ews_asignatura_sw;
        $asig_docen->CAMPUS = $request->ews_campus_sw;
        $asig_docen->ID_DOCENTE = $request->ews_id_docente_sw;

        $hora_dispo = new HORARIO_DISPONIBLE();
        $hora_dispo->DIA = $request->ews_dia_sw;
        $hora_dispo->HORA_INICIO = $request->ews_hora_inicio_sw;
        $hora_dispo->HORA_FIN = $request->ews_hora_fin_sw;
        $hora_dispo->ID_DOCENTE = $request->ews_id_docente_sw;

        $docente->save();
        $asig_docen->save();
        $hora_dispo->save();

        return response()->json('Datos Guardados');
    }
 
}
