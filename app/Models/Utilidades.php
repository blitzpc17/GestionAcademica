<?php
namespace App\Models;

use DateTime;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;


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

    public static function GenerarZip($nombreZip,$subdir, $arrFiles){
        $zip = new ZipArchive;
        $rutaZip = storage_path($nombreZip.".zip");
    
        if ($zip->open($rutaZip, ZipArchive::CREATE) === true) {
            foreach ($arrFiles as $archivo) {
                // Obtiene el contenido del archivo desde el sistema de almacenamiento
                $contenidoArchivo = Storage::get($subdir.$archivo);
    
                // Agrega el contenido al archivo zip
                $nombreArchivo = basename($archivo);
                $zip->addFromString($nombreArchivo, $contenidoArchivo);
            }
    
            $zip->close();


            return $rutaZip;
        } else {
            return null;
        }
    }


}