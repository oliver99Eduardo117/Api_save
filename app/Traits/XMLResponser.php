<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Solicitud;
use App\Models\EstadoTramite;
use App\Models\Token;
use App\Models\Token_accesos;
use DOMDocument;

trait XMLResponser{


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

    protected function errorResponseXML($message, $code, $id_token, $validated, $Dat_Solicitud, $mytime, $url){
         $this->saveTokenAccess( $id_token, $message, $code);
         return response()->json([
            'wsp_mensaje' => $message,
            'wsp_no_solicitud' => $validated['ews_no_solicitud'], //$request->no_solicitud,
            'wsp_no_solicitud_api' => $Dat_Solicitud->no_solicitud_api,
            'wsp_fecha_generacion ' => $mytime->toDateString(),
            'wsp_hora_generacion' => $mytime->toTimeString(),
            'wsp_url_xml' => $url . $Dat_Solicitud->id_solicitud  . '.xml' , // Cambiar por el URL
            ],$code);
    }

    protected function generarXML($Dat_Solicitud, $Dat_Extra, $request)
    {
        // Estructura XML
        $xml = new DOMDocument('1.0','UTF-8');

        $datos = $xml->createElement('Documento');
        $xml->appendChild($datos);

        $id_solicitud = $xml->createElement('id_solicitud',$Dat_Solicitud->id_solicitud);
        $datos->appendChild($id_solicitud);

        $id_tramite = $xml->createElement('id_tramite',$Dat_Solicitud->id_tramite);
        $datos->appendChild($id_tramite);

        $no_solicitud = $xml->createElement('no_solicitud',$Dat_Solicitud->id_tramite);
        $datos->appendChild($no_solicitud);

        $fecha_solicitud = $xml->createElement('fecha_solicitud',$Dat_Solicitud->fecha_solicitud);
        $datos->appendChild($fecha_solicitud);

        $hora_solicitud = $xml->createElement('hora_solicitud',$Dat_Solicitud->hora_solicitud);
        $datos->appendChild($hora_solicitud);

        $no_solicitud_api = $xml->createElement('no_solicitud_api',$Dat_Solicitud->no_solicitud_api);
        $datos->appendChild($no_solicitud_api);

        $fecha_solicitud_api = $xml->createElement('fecha_solicitud',$Dat_Solicitud->fecha_solicitud_api);
        $datos->appendChild($fecha_solicitud_api);

        $id_electronico = $xml->createElement('ews_id_electronico',$Dat_Solicitud->id_electronico);
        $datos->appendChild($id_electronico);

        $referencia_pago = $xml->createElement('ews_referencia_pago',$Dat_Solicitud->referencia_pago);
        $datos->appendChild($referencia_pago);

        $fecha_pago = $xml->createElement('ews_fecha_pago',$Dat_Solicitud->fecha_pago);
        $datos->appendChild($fecha_pago);

        $hora_pago = $xml->createElement('ews_hora_pago',$Dat_Solicitud->hora_pago);
        $datos->appendChild($hora_pago);

        $stripe_orden_id = $xml->createElement('ews_stripe_orden_id',$Dat_Solicitud->stripe_orden_id);
        $datos->appendChild($stripe_orden_id);

        $stripe_creado = $xml->createElement('ews_stripe_creado',$Dat_Solicitud->stripe_creado);
        $datos->appendChild($stripe_creado);

        $stripe_mensaje = $xml->createElement('ews_stripe_mensaje',$Dat_Solicitud->stripe_mensaje);
        $datos->appendChild($stripe_mensaje);

        $stripe_tipo = $xml->createElement('ews_stripe_tipo',$Dat_Solicitud->stripe_tipo);
        $datos->appendChild($stripe_tipo);

        $stripe_digitos = $xml->createElement('ews_stripe_digitos',$Dat_Solicitud->stripe_digitos);
        $datos->appendChild($stripe_digitos);

        $stripe_red = $xml->createElement('ews_stripe_red',$Dat_Solicitud->stripe_red);
        $datos->appendChild($stripe_red);

        $stripe_estado = $xml->createElement('ews_stripe_estado',$Dat_Solicitud->stripe_estado);
        $datos->appendChild($stripe_estado);

        $no_licencia = $xml->createElement('ews_no_licencia',$Dat_Solicitud->no_licencia);
        $datos->appendChild($no_licencia);
        
        $xml_url = $xml->createElement('ews_xml_url',$Dat_Solicitud->xml_url);
        $datos->appendChild($xml_url);



        // Datos Extra
        /*
        $Dat_Alum = $xml->createElement('Datos_Extra');
        $datos->appendChild($Dat_Alum);

        $dato = $xml->createElement('ejemplo',$Dat_Extra->ejem);
        $Dat_Alum->appendChild($dato);
        */
        // Guardar XML
        $xml->formatOutput = true;
        $el_xml = $xml->saveXML();
        $xml->save('../storage/app/public/xml/'.$Dat_Solicitud->id_solicitud . '.xml');
        htmlentities($el_xml);

        // Actualizar Estado
        Solicitud::Where("no_solicitud", $request->ews_no_solicitud)
        ->update([ 'id_estado' => EstadoTramite::XML_GENERADO ]);
    }
}
