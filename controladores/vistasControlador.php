<?php

    require_once "./modelos/vistasModelo.php";

    // controlador para determinar que vista usar
    
    class vistasControlador extends vistasModelo {

        /* controlador obtener plantilla */
        public function obtener_plantilla_controlador(){
            return require_once "./vistas/plantilla.php";
        }

        /* controlador obtener vistas */
        // como si fuera el router
        public function obtener_vistas_controlador(){
            if (isset($_GET['views'])) {
                // separa la ruta usando el delimitador /
                $ruta=explode("/",$_GET['views']);
                // regresa el contenido si existe esa vista
                $respuesta=vistasModelo::obtener_vistas_modelo($ruta[0]);
            }else{
                $respuesta= "login";
            }
            return $respuesta;
        }
    }