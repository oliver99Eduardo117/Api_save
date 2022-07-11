<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;

class Api1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    
    public function store(Request $request){
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
            'ews_llave' => 'required|string|max:100',
            'ews_id_tramite' => 'required|integer', /*|in:.$idTramite.''*/
            'ews_no_solicitud' => 'required|string|max:20',
            'ews_fecha_solicitud' => 'required|date_format:Y-m-d',
            'ews_hora_solicitud' => 'required|date_format:H:i:s',

            'ews_correo_sw' => 'required|string|max:18|exists:App\Models\Solicitud,curp',
            'ews_telefono_sw' => 'required',
            'ews_campus_sw' => 'required',
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

        $validated = $validator->validated();
        
        //$Dato_db = \DB::table('solicitudes')->where('curp','no_licencia' )->get();
        //dd($Dato_db[0]->no_licencia);
        //dd($request);
        //$Dato_db = Solicitud::where('id_solicitud')->where('curp', $request)->get();
        //$Dato_db = Solicitud::Where("no_licencia", $validated['no_licencia'])->get();
        //$Dato_db = Solicitud::Where('no_licencia', $validated['no_licencia'])->get();
        //dd($validated['no_licencia']);

        /*valida que exista la licencia y la curp en la bd
        $Dato_db = Solicitud::select('no_licencia')->get();
        if($Dato_db[0]->no_licencia !== $validated['ews_no_licencia']){
            $mensaje = 'Numero de licencia: No existe '. $erroresValidacion;
            return $this->errorResponse($mensaje,403,1);
        }

        $Dato_db = Solicitud::select('curp')->get();
        //dd($Dato_db[0]->curp);
        //dd($validated['curp']);
        if($Dato_db[0]->curp !== $validated['ews_curp_sw']){
            $mensaje= 'Curp del ciudadano No existe'. $erroresValidacion;
        return $this->errorResponse($mensaje,403, 1);
        }*/
            
        // Estructura Solicitud
        $solicitud = new Solicitud();
        $solicitud->llave = $validated['ews_llave'];
        $solicitud->id_tramite = $validated['ews_id_tramite'];
        $solicitud->fecha_solicitud = $validated['ews_fecha_solicitud'];
        $solicitud->hora_solicitud = $validated['ews_hora_solicitud'];
        $solicitud->no_solicitud = $validated['ews_no_solicitud'];
        $solicitud->no_licencia = $validated['ews_no_licencia'];
        $solicitud->curp = $validated['ews_curp_sw'];

        $solicitud->no_solicitud_api = '' . (Solicitud::all()->count() + 1);
        $solicitud->fecha_solicitud_api = $mytime->toDateString();
        $solicitud->hora_solicitud_api = $mytime->toTimeString();

        $solicitud->nombre = $request->ews_nombre_sw;
        $solicitud->apellido_paterno = $request->ews_apellido_paterno_sw;
        $solicitud->apellido_materno = $request->ews_apellido_materno_sw;   
        $solicitud->correo = $request->ews_correo_sw;
        //$solicitud->foto = $request->ews_url_foto;
        $solicitud->curp = $request->ews_curp_sw; 
        $solicitud->telefono = $request->ews_telefono_sw;
        $solicitud->fecha_nacimiento = $request->ews_fecha_nacimiento_sw;
        $solicitud->sexo = $request->ews_sexo_sw;
        $solicitud->tipo_sanguineo = $request->ews_tipo_sanguineo;
        $solicitud->codigo_postal = $request->ews_codigo_postal_sw;
        $solicitud->no_licencia = $request->ews_no_licencia;
        $solicitud->tipo_licencia = $request->ews_tipo_licencia;
        $solicitud->save();

        return $this->jsonResponse($request, null, $solicitud,);
    }
 
}
