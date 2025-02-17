<?php

    class vistasModelo {
        
        /*------------- modelo obtener vistas ------- */
        // depende de la ruta tecleada en la url
        // similar al router
        protected static function obtener_vistas_modelo($vistas) {
            $listaBlanca=[];
            if (in_array($vistas, $listaBlanca)){
                if (is_file("./vistas/contenidos/".$vistas."-view.php")){
                    $contenido= "./vistas/contenidos/".$vistas."-view.php";
                }else {
                    $contenido="404";
                }
            }else if ($vistas=="login" || $vistas=="index"){
                $contenido= "login";
            } else {
                $contenido="404";
            }
            return $contenido;
        }
    }