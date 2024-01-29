<?php
namespace App\Models;

use DateTime;


class Utilidades {

    public static function MensajesValidaciones(){
        return $msj = array (
            'required' => "El campo :attribute es obligatorio.",
            "string" => "El valor de ser de tipo Texto.",
            "max" => "Máximo :min carácteres.",
            "min" => "Mínimo :min carácteres.",
            "date" => "El valor debe ser de tipo Fecha.",
            "mimes" => "Solo se permiten archivos .pdf, .doc, .docx.",
            "rol.min" => "Seleccione una opción válida.",
            "modulo.min" => "Seleccione una opción válida."
        );
    }

    public static function FormatearFecha($formatoOrigen, $fecha, $formatoFinal){
        $fechaObjeto = DateTime::createFromFormat($formatoOrigen, $fecha);
        return $fechaObjeto->format($formatoFinal);
    }


}